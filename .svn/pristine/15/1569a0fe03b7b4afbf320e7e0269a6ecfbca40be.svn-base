/*
 Copyright (C) 2002-2004 MySQL AB

 This program is free software; you can redistribute it and/or modify
 it under the terms of version 2 of the GNU General Public License as 
 published by the Free Software Foundation.

 There are special exceptions to the terms and conditions of the GPL 
 as it is applied to this software. View the full text of the 
 exception in file EXCEPTIONS-CONNECTOR-J in the directory of this 
 software distribution.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA



 */
package testsuite.regression;

import java.sql.CallableStatement;
import java.sql.Connection;
import java.sql.SQLException;
import java.sql.Types;

import com.mysql.jdbc.DatabaseMetaData;
import com.mysql.jdbc.SQLError;

import testsuite.BaseTestCase;

/**
 * Tests fixes for bugs in CallableStatement code.
 * 
 * @version $Id: CallableStatementRegressionTest.java,v 1.1.2.6 2004/12/09
 *          15:57:26 mmatthew Exp $
 */
public class CallableStatementRegressionTest extends BaseTestCase {
	/**
	 * DOCUMENT ME!
	 * 
	 * @param name
	 */
	public CallableStatementRegressionTest(String name) {
		super(name);

		// TODO Auto-generated constructor stub
	}

	/**
	 * Runs all test cases in this test suite
	 * 
	 * @param args
	 *            ignored
	 */
	public static void main(String[] args) {
		junit.textui.TestRunner.run(CallableStatementRegressionTest.class);
	}

	/**
	 * Tests fix for BUG#3539 getProcedures() does not return any procedures in
	 * result set
	 * 
	 * @throws Exception
	 *             if an error occurs.
	 */
	public void testBug3539() throws Exception {
		if (versionMeetsMinimum(5, 0)) {
			try {
				this.stmt.executeUpdate("DROP PROCEDURE IF EXISTS testBug3539");
				this.stmt.executeUpdate("CREATE PROCEDURE testBug3539()\n"
						+ "BEGIN\n" + "SELECT 1;" + "end\n");

				this.rs = this.conn.getMetaData().getProcedures(null, null,
						"testBug3539");

				assertTrue(this.rs.next());
				assertTrue("testBug3539".equals(this.rs.getString(3)));
			} finally {
				this.stmt.executeUpdate("DROP PROCEDURE IF EXISTS testBug3539");
			}
		}
	}

	/**
	 * Tests fix for BUG#3540 getProcedureColumns doesn't work with wildcards
	 * for procedure name
	 * 
	 * @throws Exception
	 *             if an error occurs.
	 */
	public void testBug3540() throws Exception {
		if (versionMeetsMinimum(5, 0)) {
			try {
				this.stmt.executeUpdate("DROP PROCEDURE IF EXISTS testBug3540");
				this.stmt
						.executeUpdate("CREATE PROCEDURE testBug3540(x int, out y int)\n"
								+ "BEGIN\n" + "SELECT 1;" + "end\n");

				this.rs = this.conn.getMetaData().getProcedureColumns(null,
						null, "testBug3540%", "%");

				assertTrue(this.rs.next());
				assertTrue("testBug3540".equals(this.rs.getString(3)));
				assertTrue("x".equals(this.rs.getString(4)));

				assertTrue(this.rs.next());
				assertTrue("testBug3540".equals(this.rs.getString(3)));
				assertTrue("y".equals(this.rs.getString(4)));

				assertTrue(!this.rs.next());
			} finally {
				this.stmt.executeUpdate("DROP PROCEDURE IF EXISTS testBug3540");
			}
		}
	}

	/**
	 * Tests fix for BUG#7026 - DBMD.getProcedures() doesn't respect catalog
	 * parameter
	 * 
	 * @throws Exception
	 *             if the test fails.
	 */
	public void testBug7026() throws Exception {
		if (versionMeetsMinimum(5, 0)) {
			try {
				this.stmt.executeUpdate("DROP PROCEDURE IF EXISTS testBug7026");
				this.stmt
						.executeUpdate("CREATE PROCEDURE testBug7026(x int, out y int)\n"
								+ "BEGIN\n" + "SELECT 1;" + "end\n");

				//
				// Should be found this time.
				//
				this.rs = this.conn.getMetaData().getProcedures(
						this.conn.getCatalog(), null, "testBug7026");

				assertTrue(this.rs.next());
				assertTrue("testBug7026".equals(this.rs.getString(3)));

				assertTrue(!this.rs.next());

				//
				// This time, shouldn't be found, because not associated with
				// this (bogus) catalog
				//
				this.rs = this.conn.getMetaData().getProcedures("abfgerfg",
						null, "testBug7026");
				assertTrue(!this.rs.next());

				//
				// Should be found this time as well, as we haven't
				// specified a catalog.
				//
				this.rs = this.conn.getMetaData().getProcedures(null, null,
						"testBug7026");

				assertTrue(this.rs.next());
				assertTrue("testBug7026".equals(this.rs.getString(3)));

				assertTrue(!this.rs.next());
			} finally {
				this.stmt.executeUpdate("DROP PROCEDURE IF EXISTS testBug7026");
			}
		}
	}

	/**
	 * Tests fix for BUG#9319 -- Stored procedures with same name in different
	 * databases confuse the driver when it tries to determine parameter
	 * counts/types.
	 * 
	 * @throws Exception
	 *             if the test fails
	 */
	public void testBug9319() throws Exception {
		boolean doASelect = true; // SELECT currently causes the server to
									// hang on the
		// last execution of this testcase, filed as BUG#9405

		if (versionMeetsMinimum(5, 0, 2)) {
			if (isAdminConnectionConfigured()) {
				Connection db2Connection = null;
				Connection db1Connection = null;

				try {
					db2Connection = getAdminConnection();
					db1Connection = getAdminConnection();

					db2Connection.createStatement().executeUpdate(
							"CREATE DATABASE IF NOT EXISTS db_9319_2");
					db2Connection.setCatalog("db_9319_2");

					db2Connection.createStatement().executeUpdate(
							"DROP PROCEDURE IF EXISTS COMPROVAR_USUARI");

					db2Connection
							.createStatement()
							.executeUpdate(
									"CREATE PROCEDURE COMPROVAR_USUARI(IN p_CodiUsuari VARCHAR(10),"
											+ "\nIN p_contrasenya VARCHAR(10),"
											+ "\nOUT p_userId INTEGER,"
											+ "\nOUT p_userName VARCHAR(30),"
											+ "\nOUT p_administrador VARCHAR(1),"
											+ "\nOUT p_idioma VARCHAR(2))"
											+ "\nBEGIN"

											+ (doASelect ? "\nselect 2;"
													: "\nSELECT 2 INTO p_administrador;")
											+ "\nEND");

					db1Connection.createStatement().executeUpdate(
							"CREATE DATABASE IF NOT EXISTS db_9319_1");
					db1Connection.setCatalog("db_9319_1");

					db1Connection.createStatement().executeUpdate(
							"DROP PROCEDURE IF EXISTS COMPROVAR_USUARI");
					db1Connection
							.createStatement()
							.executeUpdate(
									"CREATE PROCEDURE COMPROVAR_USUARI(IN p_CodiUsuari VARCHAR(10),"
											+ "\nIN p_contrasenya VARCHAR(10),"
											+ "\nOUT p_userId INTEGER,"
											+ "\nOUT p_userName VARCHAR(30),"
											+ "\nOUT p_administrador VARCHAR(1))"
											+ "\nBEGIN"
											+ (doASelect ? "\nselect 1;"
													: "\nSELECT 1 INTO p_administrador;")
											+ "\nEND");

					CallableStatement cstmt = db2Connection
							.prepareCall("{ call COMPROVAR_USUARI(?, ?, ?, ?, ?, ?) }");
					cstmt.setString(1, "abc");
					cstmt.setString(2, "def");
					cstmt.registerOutParameter(3, java.sql.Types.INTEGER);
					cstmt.registerOutParameter(4, java.sql.Types.VARCHAR);
					cstmt.registerOutParameter(5, java.sql.Types.VARCHAR);

					cstmt.registerOutParameter(6, java.sql.Types.VARCHAR);

					cstmt.execute();

					if (doASelect) {
						this.rs = cstmt.getResultSet();
						assertTrue(this.rs.next());
						assertEquals(2, this.rs.getInt(1));
					} else {
						assertEquals(2, cstmt.getInt(5));
					}

					cstmt = db1Connection
							.prepareCall("{ call COMPROVAR_USUARI(?, ?, ?, ?, ?, ?) }");
					cstmt.setString(1, "abc");
					cstmt.setString(2, "def");
					cstmt.registerOutParameter(3, java.sql.Types.INTEGER);
					cstmt.registerOutParameter(4, java.sql.Types.VARCHAR);
					cstmt.registerOutParameter(5, java.sql.Types.VARCHAR);

					try {
						cstmt.registerOutParameter(6, java.sql.Types.VARCHAR);
						fail("Should've thrown an exception");
					} catch (SQLException sqlEx) {
						assertEquals(SQLError.SQL_STATE_ILLEGAL_ARGUMENT, sqlEx
								.getSQLState());
					}

					cstmt = db1Connection
							.prepareCall("{ call COMPROVAR_USUARI(?, ?, ?, ?, ?) }");
					cstmt.setString(1, "abc");
					cstmt.setString(2, "def");
					cstmt.registerOutParameter(3, java.sql.Types.INTEGER);
					cstmt.registerOutParameter(4, java.sql.Types.VARCHAR);
					cstmt.registerOutParameter(5, java.sql.Types.VARCHAR);

					cstmt.execute();

					if (doASelect) {
						this.rs = cstmt.getResultSet();
						assertTrue(this.rs.next());
						assertEquals(1, this.rs.getInt(1));
					} else {
						assertEquals(1, cstmt.getInt(5));
					}

					String quoteChar = db2Connection.getMetaData()
							.getIdentifierQuoteString();

					cstmt = db2Connection.prepareCall("{ call " + quoteChar
							+ db1Connection.getCatalog() + quoteChar + "."
							+ quoteChar + "COMPROVAR_USUARI" + quoteChar
							+ "(?, ?, ?, ?, ?) }");
					cstmt.setString(1, "abc");
					cstmt.setString(2, "def");
					cstmt.registerOutParameter(3, java.sql.Types.INTEGER);
					cstmt.registerOutParameter(4, java.sql.Types.VARCHAR);
					cstmt.registerOutParameter(5, java.sql.Types.VARCHAR);

					cstmt.execute();

					if (doASelect) {
						this.rs = cstmt.getResultSet();
						assertTrue(this.rs.next());
						assertEquals(1, this.rs.getInt(1));
					} else {
						assertEquals(1, cstmt.getInt(5));
					}
				} finally {
					if (db2Connection != null) {
						db2Connection.createStatement().executeUpdate(
								"DROP PROCEDURE IF EXISTS COMPROVAR_USUARI");
						db2Connection.createStatement().executeUpdate(
								"DROP DATABASE IF EXISTS db_9319_2");
					}

					if (db1Connection != null) {
						db1Connection.createStatement().executeUpdate(
								"DROP PROCEDURE IF EXISTS COMPROVAR_USUARI");
						db1Connection.createStatement().executeUpdate(
								"DROP DATABASE IF EXISTS db_9319_1");
					}
				}
			}
		}
	}

	/*
	 * public void testBug9319() throws Exception { boolean doASelect = false; //
	 * SELECT currently causes the server to hang on the // last execution of
	 * this testcase, filed as BUG#9405
	 * 
	 * if (versionMeetsMinimum(5, 0, 2)) { if (isAdminConnectionConfigured()) {
	 * Connection db2Connection = null; Connection db1Connection = null;
	 * 
	 * try { db2Connection = getAdminConnection();
	 * 
	 * db2Connection.createStatement().executeUpdate( "CREATE DATABASE IF NOT
	 * EXISTS db_9319"); db2Connection.setCatalog("db_9319");
	 * 
	 * db2Connection.createStatement().executeUpdate( "DROP PROCEDURE IF EXISTS
	 * COMPROVAR_USUARI");
	 * 
	 * db2Connection.createStatement().executeUpdate( "CREATE PROCEDURE
	 * COMPROVAR_USUARI(IN p_CodiUsuari VARCHAR(10)," + "\nIN p_contrasenya
	 * VARCHAR(10)," + "\nOUT p_userId INTEGER," + "\nOUT p_userName
	 * VARCHAR(30)," + "\nOUT p_administrador VARCHAR(1)," + "\nOUT p_idioma
	 * VARCHAR(2))" + "\nBEGIN"
	 *  + (doASelect ? "\nselect 2;" : "\nSELECT 2 INTO p_administrador;" ) +
	 * "\nEND");
	 * 
	 * this.stmt .executeUpdate("DROP PROCEDURE IF EXISTS COMPROVAR_USUARI");
	 * this.stmt .executeUpdate("CREATE PROCEDURE COMPROVAR_USUARI(IN
	 * p_CodiUsuari VARCHAR(10)," + "\nIN p_contrasenya VARCHAR(10)," + "\nOUT
	 * p_userId INTEGER," + "\nOUT p_userName VARCHAR(30)," + "\nOUT
	 * p_administrador VARCHAR(1))" + "\nBEGIN" + (doASelect ? "\nselect 1;" :
	 * "\nSELECT 1 INTO p_administrador;" ) + "\nEND");
	 * 
	 * CallableStatement cstmt = db2Connection .prepareCall("{ call
	 * COMPROVAR_USUARI(?, ?, ?, ?, ?, ?) }"); cstmt.setString(1, "abc");
	 * cstmt.setString(2, "def"); cstmt.registerOutParameter(3,
	 * java.sql.Types.INTEGER); cstmt.registerOutParameter(4,
	 * java.sql.Types.VARCHAR); cstmt.registerOutParameter(5,
	 * java.sql.Types.VARCHAR);
	 * 
	 * cstmt.registerOutParameter(6, java.sql.Types.VARCHAR);
	 * 
	 * cstmt.execute();
	 * 
	 * if (doASelect) { this.rs = cstmt.getResultSet();
	 * assertTrue(this.rs.next()); assertEquals(2, this.rs.getInt(1)); } else {
	 * assertEquals(2, cstmt.getInt(5)); }
	 * 
	 * cstmt = this.conn .prepareCall("{ call COMPROVAR_USUARI(?, ?, ?, ?, ?, ?)
	 * }"); cstmt.setString(1, "abc"); cstmt.setString(2, "def");
	 * cstmt.registerOutParameter(3, java.sql.Types.INTEGER);
	 * cstmt.registerOutParameter(4, java.sql.Types.VARCHAR);
	 * cstmt.registerOutParameter(5, java.sql.Types.VARCHAR);
	 * 
	 * try { cstmt.registerOutParameter(6, java.sql.Types.VARCHAR);
	 * fail("Should've thrown an exception"); } catch (SQLException sqlEx) {
	 * assertEquals(SQLError.SQL_STATE_ILLEGAL_ARGUMENT, sqlEx .getSQLState()); }
	 * 
	 * cstmt = this.conn .prepareCall("{ call COMPROVAR_USUARI(?, ?, ?, ?, ?)
	 * }"); cstmt.setString(1, "abc"); cstmt.setString(2, "def");
	 * cstmt.registerOutParameter(3, java.sql.Types.INTEGER);
	 * cstmt.registerOutParameter(4, java.sql.Types.VARCHAR);
	 * cstmt.registerOutParameter(5, java.sql.Types.VARCHAR);
	 * 
	 * cstmt.execute();
	 * 
	 * if (doASelect) { this.rs = cstmt.getResultSet();
	 * assertTrue(this.rs.next()); assertEquals(1, this.rs.getInt(1)); } else {
	 * assertEquals(1, cstmt.getInt(5)); }
	 * 
	 * String quoteChar =
	 * db2Connection.getMetaData().getIdentifierQuoteString();
	 * 
	 * cstmt = db2Connection .prepareCall("{ call " + quoteChar +
	 * this.conn.getCatalog() + quoteChar + "." + quoteChar + "COMPROVAR_USUARI" +
	 * quoteChar + "(?, ?, ?, ?, ?) }"); cstmt.setString(1, "abc");
	 * cstmt.setString(2, "def"); cstmt.registerOutParameter(3,
	 * java.sql.Types.INTEGER); cstmt.registerOutParameter(4,
	 * java.sql.Types.VARCHAR); cstmt.registerOutParameter(5,
	 * java.sql.Types.VARCHAR);
	 * 
	 * cstmt.execute();
	 * 
	 * if (doASelect) { this.rs = cstmt.getResultSet();
	 * assertTrue(this.rs.next()); assertEquals(1, this.rs.getInt(1)); } else {
	 * assertEquals(1, cstmt.getInt(5)); } } finally { if (db2Connection !=
	 * null) { db2Connection.createStatement().executeUpdate( "DROP PROCEDURE IF
	 * EXISTS COMPROVAR_USUARI"); //
	 * db2Connection.createStatement().executeUpdate( // "DROP DATABASE IF
	 * EXISTS db_9319"); }
	 * 
	 * this.stmt .executeUpdate("DROP PROCEDURE IF EXISTS COMPROVAR_USUARI"); } } } }
	 */

	/**
	 * Tests fix for BUG#9682 - Stored procedures with DECIMAL parameters with
	 * storage specifications that contained "," in them would fail.
	 * 
	 * @throws Exception
	 *             if the test fails.
	 */
	public void testBug9682() throws Exception {
		if (versionMeetsMinimum(5, 0)) {
			CallableStatement cStmt = null;

			try {
				this.stmt.executeUpdate("DROP PROCEDURE IF EXISTS testBug9682");
				this.stmt
						.executeUpdate("CREATE PROCEDURE testBug9682(decimalParam DECIMAL(18,0))"
								+ "\nBEGIN" + "\n   SELECT 1;" + "\nEND");
				cStmt = this.conn.prepareCall("Call testBug9682(?)");
				cStmt.setDouble(1, 18.0);
				cStmt.execute();
			} finally {
				if (cStmt != null) {
					cStmt.close();
				}

				this.stmt.executeUpdate("DROP PROCEDURE IF EXISTS testBug9682");
			}
		}
	}

	/**
	 * Tests fix forBUG#10310 - Driver doesn't support {?=CALL(...)} for calling
	 * stored functions. This involved adding support for function retrieval to
	 * DatabaseMetaData.getProcedures() and getProcedureColumns() as well.
	 * 
	 * @throws Exception
	 *             if the test fails.
	 */
	public void testBug10310() throws Exception {
		if (versionMeetsMinimum(5, 0)) {
			CallableStatement cStmt = null;

			try {
				this.stmt.executeUpdate("DROP FUNCTION IF EXISTS testBug10310");
				this.stmt
						.executeUpdate("CREATE FUNCTION testBug10310(a float) RETURNS INT"
								+ "\nBEGIN" + "\nRETURN a;" + "\nEND");
				cStmt = this.conn.prepareCall("{? = CALL testBug10310(?)}");
				cStmt.registerOutParameter(1, Types.INTEGER);
				cStmt.setFloat(1, 2);
				assertFalse(cStmt.execute());
				assertEquals(2f, cStmt.getInt(1), .001);
				assertEquals("java.lang.Integer", cStmt.getObject(1).getClass()
						.getName());

				assertEquals(-1, cStmt.executeUpdate());
				assertEquals(2f, cStmt.getInt(1), .001);
				assertEquals("java.lang.Integer", cStmt.getObject(1).getClass()
						.getName());

				// Check metadata while we're at it

				java.sql.DatabaseMetaData dbmd = this.conn.getMetaData();

				this.rs = dbmd.getProcedures(this.conn.getCatalog(), null,
						"testBug10310");
				this.rs.next();
				assertEquals("testBug10310", this.rs
						.getString("PROCEDURE_NAME"));
				assertEquals(DatabaseMetaData.procedureReturnsResult, this.rs
						.getShort("PROCEDURE_TYPE"));
				cStmt.setNull(1, Types.FLOAT);

				assertFalse(cStmt.execute());
				assertEquals(0f, cStmt.getInt(1), .001);
				assertEquals(true, cStmt.wasNull());
				assertEquals(null, cStmt.getObject(1));
				assertEquals(true, cStmt.wasNull());

				assertEquals(-1, cStmt.executeUpdate());
				assertEquals(0f, cStmt.getInt(1), .001);
				assertEquals(true, cStmt.wasNull());
				assertEquals(null, cStmt.getObject(1));
				assertEquals(true, cStmt.wasNull());

			} finally {
				if (this.rs != null) {
					this.rs.close();
					this.rs = null;
				}

				if (cStmt != null) {
					cStmt.close();
				}

				this.stmt.executeUpdate("DROP FUNCTION IF EXISTS testBug10310");
			}
		}
	}
}
