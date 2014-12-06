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
package testsuite;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import java.util.Properties;

import junit.framework.TestCase;

/**
 * Base class for all test cases. Creates connections, statements, etc. and
 * closes them.
 * 
 * @author Mark Matthews
 * @version $Id: BaseTestCase.java,v 1.1.2.2 2005/05/19 15:52:24 mmatthews Exp $
 */
public abstract class BaseTestCase extends TestCase {
	/**
	 * JDBC URL, initialized from com.mysql.jdbc.testsuite.url system property,
	 * or defaults to jdbc:mysql:///test
	 */
	protected static String dbUrl = "jdbc:mysql:///test";

	/** Instance counter */
	private static int instanceCount = 1;

	private final static String ADMIN_CONNECTION_PROPERTY_NAME = "com.mysql.jdbc.testsuite.admin-url";

	/** Connection to server, initialized in setUp() Cleaned up in tearDown(). */
	protected Connection conn = null;

	/**
	 * PreparedStatement to be used in tests, not initialized. Cleaned up in
	 * tearDown().
	 */
	protected PreparedStatement pstmt = null;

	/**
	 * ResultSet to be used in tests, not initialized. Cleaned up in tearDown().
	 */
	protected ResultSet rs = null;

	/**
	 * Statement to be used in tests, initialized in setUp(). Cleaned up in
	 * tearDown().
	 */
	protected Statement stmt = null;

	/** The driver to use */
	protected String dbClass = "com.mysql.jdbc.Driver";

	/** My instance number */
	private int myInstanceNumber = 0;

	/** list of Tables to be dropped in tearDown */
	private List createdTables;

	/**
	 * Creates a new BaseTestCase object.
	 * 
	 * @param name
	 *            The name of the JUnit test case
	 */
	public BaseTestCase(String name) {
		super(name);
		this.myInstanceNumber = instanceCount++;

		String newDbUrl = System.getProperty("com.mysql.jdbc.testsuite.url");

		if ((newDbUrl != null) && (newDbUrl.trim().length() != 0)) {
			dbUrl = newDbUrl;
		}

		String newDriver = System
				.getProperty("com.mysql.jdbc.testsuite.driver");

		if ((newDriver != null) && (newDriver.trim().length() != 0)) {
			this.dbClass = newDriver;
		}
	}

	/**
	 * Creates resources used by all tests.
	 * 
	 * @throws Exception
	 *             if an error occurs.
	 */
	public void setUp() throws Exception {
		System.out.println("Loading JDBC driver '" + this.dbClass + "'");
		Class.forName(this.dbClass).newInstance();
		System.out.println("Done.\n");
		createdTables = new ArrayList();

		// System.out.println("Establishing connection to database '" + dbUrl
		// + "'");

		if (this.dbClass.equals("gwe.sql.gweMysqlDriver")) {
			try {
				this.conn = DriverManager.getConnection(dbUrl, "", "");
			} catch (Exception ex) {
				ex.printStackTrace();
				fail();
			}
		} else {
			try {
				this.conn = DriverManager.getConnection(dbUrl);
			} catch (Exception ex) {
				ex.printStackTrace();
				fail();
			}
		}

		System.out.println("Done.\n");
		this.stmt = this.conn.createStatement();

		try {
			this.rs = this.stmt.executeQuery("SELECT VERSION()");
			this.rs.next();
			logDebug("Connected to " + this.rs.getString(1));
			this.rs.close();
			this.rs = null;
		} finally {
			if (this.rs != null) {
				this.rs.close();
			}
		}
	}

	/**
	 * Destroys resources created during the test case.
	 * 
	 * @throws Exception
	 *             DOCUMENT ME!
	 */
	public void tearDown() throws Exception {
		if (this.rs != null) {
			try {
				this.rs.close();
			} catch (SQLException SQLE) {
				;
			}
		}

		for (int i = 0; i < createdTables.size(); i++) {
			try {
				dropTable((String) createdTables.get(i));
			} catch (SQLException SQLE) {
				;
			}
		}

		if (this.stmt != null) {
			try {
				this.stmt.close();
			} catch (SQLException SQLE) {
				;
			}
		}

		if (this.pstmt != null) {
			try {
				this.pstmt.close();
			} catch (SQLException SQLE) {
				;
			}
		}

		if (this.conn != null) {
			try {
				this.conn.close();
			} catch (SQLException SQLE) {
				;
			}
		}
	}

	protected Connection getAdminConnection() throws SQLException {
		return getAdminConnectionWithProps(new Properties());
	}

	protected boolean isAdminConnectionConfigured() {
		return System.getProperty(ADMIN_CONNECTION_PROPERTY_NAME) != null;
	}

	protected Connection getAdminConnectionWithProps(Properties props)
			throws SQLException {
		String adminUrl = System.getProperty(ADMIN_CONNECTION_PROPERTY_NAME);

		if (adminUrl != null) {
			return DriverManager.getConnection(adminUrl, props);
		} else {
			return null;
		}
	}

	/**
	 * Returns a new connection with the given properties
	 * 
	 * @param props
	 *            the properties to use (the URL will come from the standard for
	 *            this testcase).
	 * 
	 * @return a new connection using the given properties.
	 * 
	 * @throws SQLException
	 *             DOCUMENT ME!
	 */
	protected Connection getConnectionWithProps(Properties props)
			throws SQLException {
		return DriverManager.getConnection(dbUrl, props);
	}

	/**
	 * Returns the per-instance counter (for messages when multi-threading
	 * stress tests)
	 * 
	 * @return int the instance number
	 */
	protected int getInstanceNumber() {
		return this.myInstanceNumber;
	}

	/**
	 * Returns the named MySQL variable from the currently connected server.
	 * 
	 * @param variableName
	 *            the name of the variable to return
	 * 
	 * @return the value of the given variable, or NULL if it doesn't exist
	 * 
	 * @throws SQLException
	 *             if an error occurs
	 */
	protected String getMysqlVariable(String variableName) throws SQLException {
		return getMysqlVariable(this.conn, variableName);
	}

	protected String getMysqlVariable(Connection c, String variableName)
			throws SQLException {
		Object value = getSingleIndexedValueWithQuery(c, 2,
				"SHOW VARIABLES LIKE '" + variableName + "'");

		if (value != null) {
			return value.toString();
		}

		return null;

	}

	protected int getRowCount(String tableName) throws SQLException {
		ResultSet countRs = null;

		try {
			countRs = this.stmt.executeQuery("SELECT COUNT(*) FROM "
					+ tableName);

			countRs.next();

			return countRs.getInt(1);
		} finally {
			if (countRs != null) {
				countRs.close();
			}
		}
	}

	protected Object getSingleIndexedValueWithQuery(int columnIndex,
			String query) throws SQLException {
		return getSingleIndexedValueWithQuery(this.conn, columnIndex, query);
	}

	protected Object getSingleIndexedValueWithQuery(Connection c,
			int columnIndex, String query) throws SQLException {
		ResultSet valueRs = null;

		Statement svStmt = null;

		try {
			svStmt = c.createStatement();

			valueRs = svStmt.executeQuery(query);

			if (!valueRs.next()) {
				return null;
			}

			return valueRs.getObject(columnIndex);
		} finally {
			if (valueRs != null) {
				valueRs.close();
			}

			if (svStmt != null) {
				svStmt.close();
			}
		}
	}

	protected Object getSingleValue(String tableName, String columnName,
			String whereClause) throws SQLException {
		return getSingleValueWithQuery("SELECT " + columnName + " FROM "
				+ tableName + ((whereClause == null) ? "" : whereClause));
	}

	protected Object getSingleValueWithQuery(String query) throws SQLException {
		return getSingleIndexedValueWithQuery(1, query);
	}

	/**
	 * Checks whether a certain system property is defined, in order to
	 * run/not-run certain tests
	 * 
	 * @param propName
	 *            the property name to check for
	 * 
	 * @return true if the property is defined.
	 */
	protected boolean runTestIfSysPropDefined(String propName) {
		String prop = System.getProperty(propName);

		return (prop != null) && (prop.length() > 0);
	}

	/**
	 * Checks whether the database we're connected to meets the given version
	 * minimum
	 * 
	 * @param major
	 *            the major version to meet
	 * @param minor
	 *            the minor version to meet
	 * 
	 * @return boolean if the major/minor is met
	 * 
	 * @throws SQLException
	 *             if an error occurs.
	 */
	protected boolean versionMeetsMinimum(int major, int minor)
			throws SQLException {
		return versionMeetsMinimum(major, minor, 0);
	}

	/**
	 * Checks whether the database we're connected to meets the given version
	 * minimum
	 * 
	 * @param major
	 *            the major version to meet
	 * @param minor
	 *            the minor version to meet
	 * 
	 * @return boolean if the major/minor is met
	 * 
	 * @throws SQLException
	 *             if an error occurs.
	 */
	protected boolean versionMeetsMinimum(int major, int minor, int subminor)
			throws SQLException {
		return (((com.mysql.jdbc.Connection) this.conn).versionMeetsMinimum(
				major, minor, subminor));
	}

	protected void createTable(String tableName, String columnsAndOtherStuff)
			throws SQLException {
		createdTables.add(tableName);
		dropTable(tableName);

		StringBuffer createSql = new StringBuffer(tableName.length()
				+ columnsAndOtherStuff.length() + 10);
		createSql.append("CREATE TABLE ");
		createSql.append(tableName);
		createSql.append(" ");
		createSql.append(columnsAndOtherStuff);
		this.stmt.executeUpdate(createSql.toString());
	}

	protected void dropTable(String tableName) throws SQLException {
		this.stmt.executeUpdate("DROP TABLE IF EXISTS " + tableName);
	}

	public void logDebug(String message) {
		if (System.getProperty("com.mysql.jdbc.testsuite.noDebugOutput") == null) {
			System.err.println(message);
		}
	}

	protected final boolean runLongTests() {
		return runTestIfSysPropDefined("com.mysql.jdbc.testsuite.runLongTests");
	}

	protected Connection getConnectionWithProps(String url, Properties props)
			throws SQLException {
		return DriverManager.getConnection(url, props);
	}
}
