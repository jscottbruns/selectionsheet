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
package com.mysql.jdbc;

import java.io.BufferedInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.Properties;
import java.util.Set;
import java.util.SortedSet;

/**
 * Mapping between MySQL charset names and Java charset names. I've investigated
 * placing these in a .properties file, but unfortunately under most appservers
 * this complicates configuration because the security policy needs to be
 * changed by the user to allow the driver to read them :(
 * 
 * @author Mark Matthews
 */
public class CharsetMapping {
	private static final Properties CHARSET_CONFIG = new Properties();

	/**
	 * Map of MySQL-4.1 charset indexes to Java encoding names
	 */
	public static final String[] INDEX_TO_CHARSET;

	/** Mapping of Java charset names to MySQL charset names */
	private static final Map JAVA_TO_MYSQL_CHARSET_MAP;

	private static final Map JAVA_UC_TO_MYSQL_CHARSET_MAP;

	/** Map/List of multibyte character sets (using MySQL names) */
	private static final Map MULTIBYTE_CHARSETS;

	private static final Map MYSQL_TO_JAVA_CHARSET_MAP;

	static {
		InputStream inStream = CharsetMapping.class
				.getResourceAsStream("Charsets.properties");

		if (inStream == null) {
			throw new RuntimeException(
					"Unable to initialize character set mapping tables");
		}

		try {
			CHARSET_CONFIG.load(inStream);
			inStream.close();
			inStream = null;
		} catch (IOException ioEx) {
			throw new RuntimeException(
					"Unable to initialize character set mapping tables");
		} finally {

			if (inStream != null) {
				try {
					inStream.close();
				} catch (IOException ioEx) {
					// do nothing, can't handle it here
				}
			}
		}

		HashMap javaToMysqlMap = new HashMap();

		populateMapWithKeyValuePairs("javaToMysqlMappings", javaToMysqlMap,
				true, false);
		JAVA_TO_MYSQL_CHARSET_MAP = Collections.unmodifiableMap(javaToMysqlMap);

		HashMap mysqlToJavaMap = new HashMap();

		Set keySet = JAVA_TO_MYSQL_CHARSET_MAP.keySet();

		Iterator javaCharsets = keySet.iterator();

		while (javaCharsets.hasNext()) {
			Object javaEncodingName = javaCharsets.next();
			List mysqlEncodingList = (List) JAVA_TO_MYSQL_CHARSET_MAP
					.get(javaEncodingName);

			Iterator mysqlEncodings = mysqlEncodingList.iterator();

			String mysqlEncodingName = null;

			while (mysqlEncodings.hasNext()) {
				VersionedStringProperty mysqlProp = (VersionedStringProperty) mysqlEncodings
						.next();
				mysqlEncodingName = mysqlProp.toString();

				mysqlToJavaMap.put(mysqlEncodingName, javaEncodingName);
				mysqlToJavaMap.put(mysqlEncodingName
						.toUpperCase(Locale.ENGLISH), javaEncodingName);
			}
		}

		// we don't want CP932 to map to CP932
		mysqlToJavaMap.put("cp932", "Windows-31J");
		mysqlToJavaMap.put("CP932", "Windows-31J");

		MYSQL_TO_JAVA_CHARSET_MAP = Collections.unmodifiableMap(mysqlToJavaMap);

		HashMap ucMap = new HashMap(JAVA_TO_MYSQL_CHARSET_MAP.size());

		Iterator javaNamesKeys = JAVA_TO_MYSQL_CHARSET_MAP.keySet().iterator();

		while (javaNamesKeys.hasNext()) {
			String key = (String) javaNamesKeys.next();

			ucMap.put(key.toUpperCase(Locale.ENGLISH),
					JAVA_TO_MYSQL_CHARSET_MAP.get(key));
		}

		JAVA_UC_TO_MYSQL_CHARSET_MAP = Collections.unmodifiableMap(ucMap);

		//
		// Character sets that we can't convert
		// ourselves.
		//
		HashMap tempMapMulti = new HashMap();

		populateMapWithKeyValuePairs("multibyteCharsets", tempMapMulti, false,
				true);

		MULTIBYTE_CHARSETS = Collections.unmodifiableMap(tempMapMulti);

		INDEX_TO_CHARSET = new String[99];

		try {
			INDEX_TO_CHARSET[1] = getJavaEncodingForMysqlEncoding("big5", null);
			INDEX_TO_CHARSET[2] = getJavaEncodingForMysqlEncoding("czech", null);
			INDEX_TO_CHARSET[3] = getJavaEncodingForMysqlEncoding("dec8", null);
			INDEX_TO_CHARSET[4] = getJavaEncodingForMysqlEncoding("dos", null);
			INDEX_TO_CHARSET[5] = getJavaEncodingForMysqlEncoding("german1",
					null);
			INDEX_TO_CHARSET[6] = getJavaEncodingForMysqlEncoding("hp8", null);
			INDEX_TO_CHARSET[7] = getJavaEncodingForMysqlEncoding("koi8_ru",
					null);
			INDEX_TO_CHARSET[8] = getJavaEncodingForMysqlEncoding("latin1",
					null);
			INDEX_TO_CHARSET[9] = getJavaEncodingForMysqlEncoding("latin2",
					null);
			INDEX_TO_CHARSET[10] = getJavaEncodingForMysqlEncoding("swe7", null);
			INDEX_TO_CHARSET[11] = getJavaEncodingForMysqlEncoding("usa7", null);
			INDEX_TO_CHARSET[12] = getJavaEncodingForMysqlEncoding("ujis", null);
			INDEX_TO_CHARSET[13] = getJavaEncodingForMysqlEncoding("sjis", null);
			INDEX_TO_CHARSET[14] = getJavaEncodingForMysqlEncoding("cp1251",
					null);
			INDEX_TO_CHARSET[15] = getJavaEncodingForMysqlEncoding("danish",
					null);
			INDEX_TO_CHARSET[16] = getJavaEncodingForMysqlEncoding("hebrew",
					null);
			INDEX_TO_CHARSET[18] = getJavaEncodingForMysqlEncoding("tis620",
					null);
			INDEX_TO_CHARSET[19] = getJavaEncodingForMysqlEncoding("euc_kr",
					null);
			INDEX_TO_CHARSET[20] = getJavaEncodingForMysqlEncoding("estonia",
					null);
			INDEX_TO_CHARSET[21] = getJavaEncodingForMysqlEncoding("hungarian",
					null);
			INDEX_TO_CHARSET[22] = getJavaEncodingForMysqlEncoding("koi8_ukr",
					null);
			INDEX_TO_CHARSET[23] = getJavaEncodingForMysqlEncoding(
					"win1251ukr", null);
			INDEX_TO_CHARSET[24] = getJavaEncodingForMysqlEncoding("gb2312",
					null);
			INDEX_TO_CHARSET[25] = getJavaEncodingForMysqlEncoding("greek",
					null);
			INDEX_TO_CHARSET[26] = getJavaEncodingForMysqlEncoding("win1250",
					null);
			INDEX_TO_CHARSET[27] = getJavaEncodingForMysqlEncoding("croat",
					null);
			INDEX_TO_CHARSET[28] = getJavaEncodingForMysqlEncoding("gbk", null);
			INDEX_TO_CHARSET[29] = getJavaEncodingForMysqlEncoding("cp1257",
					null);
			INDEX_TO_CHARSET[30] = getJavaEncodingForMysqlEncoding("latin5",
					null);
			INDEX_TO_CHARSET[31] = getJavaEncodingForMysqlEncoding("latin1_de",
					null);
			INDEX_TO_CHARSET[32] = getJavaEncodingForMysqlEncoding("armscii8",
					null);
			INDEX_TO_CHARSET[33] = getJavaEncodingForMysqlEncoding("utf8", null);
			INDEX_TO_CHARSET[34] = getJavaEncodingForMysqlEncoding("win1250ch",
					null);
			INDEX_TO_CHARSET[35] = getJavaEncodingForMysqlEncoding("ucs2", null);
			INDEX_TO_CHARSET[36] = getJavaEncodingForMysqlEncoding("cp866",
					null);
			INDEX_TO_CHARSET[37] = getJavaEncodingForMysqlEncoding("keybcs2",
					null);
			INDEX_TO_CHARSET[38] = getJavaEncodingForMysqlEncoding("macce",
					null);
			INDEX_TO_CHARSET[39] = getJavaEncodingForMysqlEncoding("macroman",
					null);
			INDEX_TO_CHARSET[40] = getJavaEncodingForMysqlEncoding("pclatin2",
					null);
			INDEX_TO_CHARSET[41] = getJavaEncodingForMysqlEncoding("latvian",
					null);
			INDEX_TO_CHARSET[42] = getJavaEncodingForMysqlEncoding("latvian1",
					null);
			INDEX_TO_CHARSET[43] = getJavaEncodingForMysqlEncoding("maccebin",
					null);
			INDEX_TO_CHARSET[44] = getJavaEncodingForMysqlEncoding("macceciai",
					null);
			INDEX_TO_CHARSET[45] = getJavaEncodingForMysqlEncoding("maccecias",
					null);
			INDEX_TO_CHARSET[46] = getJavaEncodingForMysqlEncoding("maccecsas",
					null);
			INDEX_TO_CHARSET[47] = getJavaEncodingForMysqlEncoding("latin1bin",
					null);
			INDEX_TO_CHARSET[48] = getJavaEncodingForMysqlEncoding(
					"latin1cias", null);
			INDEX_TO_CHARSET[49] = getJavaEncodingForMysqlEncoding(
					"latin1csas", null);
			INDEX_TO_CHARSET[50] = getJavaEncodingForMysqlEncoding("cp1251bin",
					null);
			INDEX_TO_CHARSET[51] = getJavaEncodingForMysqlEncoding(
					"cp1251cias", null);
			INDEX_TO_CHARSET[52] = getJavaEncodingForMysqlEncoding(
					"cp1251csas", null);
			INDEX_TO_CHARSET[53] = getJavaEncodingForMysqlEncoding(
					"macromanbin", null);
			INDEX_TO_CHARSET[54] = getJavaEncodingForMysqlEncoding(
					"macromancias", null);
			INDEX_TO_CHARSET[55] = getJavaEncodingForMysqlEncoding(
					"macromanciai", null);
			INDEX_TO_CHARSET[56] = getJavaEncodingForMysqlEncoding(
					"macromancsas", null);
			INDEX_TO_CHARSET[57] = getJavaEncodingForMysqlEncoding("cp1256",
					null);
			INDEX_TO_CHARSET[63] = getJavaEncodingForMysqlEncoding("binary",
					null);
			INDEX_TO_CHARSET[64] = getJavaEncodingForMysqlEncoding("armscii",
					null);
			INDEX_TO_CHARSET[65] = getJavaEncodingForMysqlEncoding("ascii",
					null);
			INDEX_TO_CHARSET[66] = getJavaEncodingForMysqlEncoding("cp1250",
					null);
			INDEX_TO_CHARSET[67] = getJavaEncodingForMysqlEncoding("cp1256",
					null);
			INDEX_TO_CHARSET[68] = getJavaEncodingForMysqlEncoding("cp866",
					null);
			INDEX_TO_CHARSET[69] = getJavaEncodingForMysqlEncoding("dec8", null);
			INDEX_TO_CHARSET[70] = getJavaEncodingForMysqlEncoding("greek",
					null);
			INDEX_TO_CHARSET[71] = getJavaEncodingForMysqlEncoding("hebrew",
					null);
			INDEX_TO_CHARSET[72] = getJavaEncodingForMysqlEncoding("hp8", null);
			INDEX_TO_CHARSET[73] = getJavaEncodingForMysqlEncoding("keybcs2",
					null);
			INDEX_TO_CHARSET[74] = getJavaEncodingForMysqlEncoding("koi8r",
					null);
			INDEX_TO_CHARSET[75] = getJavaEncodingForMysqlEncoding("koi8ukr",
					null);
			INDEX_TO_CHARSET[77] = getJavaEncodingForMysqlEncoding("latin2",
					null);
			INDEX_TO_CHARSET[78] = getJavaEncodingForMysqlEncoding("latin5",
					null);
			INDEX_TO_CHARSET[79] = getJavaEncodingForMysqlEncoding("latin7",
					null);
			INDEX_TO_CHARSET[80] = getJavaEncodingForMysqlEncoding("cp850",
					null);
			INDEX_TO_CHARSET[81] = getJavaEncodingForMysqlEncoding("cp852",
					null);
			INDEX_TO_CHARSET[82] = getJavaEncodingForMysqlEncoding("swe7", null);
			INDEX_TO_CHARSET[83] = getJavaEncodingForMysqlEncoding("utf8", null);
			INDEX_TO_CHARSET[84] = getJavaEncodingForMysqlEncoding("big5", null);
			INDEX_TO_CHARSET[85] = getJavaEncodingForMysqlEncoding("euckr",
					null);
			INDEX_TO_CHARSET[86] = getJavaEncodingForMysqlEncoding("gb2312",
					null);
			INDEX_TO_CHARSET[87] = getJavaEncodingForMysqlEncoding("gbk", null);
			INDEX_TO_CHARSET[88] = getJavaEncodingForMysqlEncoding("sjis", null);
			INDEX_TO_CHARSET[89] = getJavaEncodingForMysqlEncoding("tis620",
					null);
			INDEX_TO_CHARSET[90] = getJavaEncodingForMysqlEncoding("ucs2", null);
			INDEX_TO_CHARSET[91] = getJavaEncodingForMysqlEncoding("ujis", null);
			INDEX_TO_CHARSET[92] = getJavaEncodingForMysqlEncoding("geostd8",
					null);
			INDEX_TO_CHARSET[93] = getJavaEncodingForMysqlEncoding("geostd8",
					null);
			INDEX_TO_CHARSET[94] = getJavaEncodingForMysqlEncoding("latin1",
					null);
			INDEX_TO_CHARSET[95] = getJavaEncodingForMysqlEncoding("cp932",
					null);
			INDEX_TO_CHARSET[96] = getJavaEncodingForMysqlEncoding("cp932",
					null);
			INDEX_TO_CHARSET[97] = getJavaEncodingForMysqlEncoding("eucjpms",
					null);
			INDEX_TO_CHARSET[98] = getJavaEncodingForMysqlEncoding("eucjpms",
					null);
		} catch (SQLException sqlEx) {
			// ignore, it won't happen in this case
		}
	}

	final static String getJavaEncodingForMysqlEncoding(String mysqlEncoding,
			Connection conn) throws SQLException {
		return (String) MYSQL_TO_JAVA_CHARSET_MAP.get(mysqlEncoding);
	}

	final static String getMysqlEncodingForJavaEncoding(String javaEncodingUC,
			Connection conn) throws SQLException {
		List mysqlEncodings = (List) CharsetMapping.JAVA_UC_TO_MYSQL_CHARSET_MAP
				.get(javaEncodingUC);
		;

		if (mysqlEncodings != null) {
			Iterator iter = mysqlEncodings.iterator();

			VersionedStringProperty versionedProp = null;

			while (iter.hasNext()) {
				VersionedStringProperty propToCheck = (VersionedStringProperty) iter
						.next();

				if (conn == null) {
					// Take the first one we get

					return propToCheck.toString();
				}

				if (versionedProp != null && !versionedProp.preferredValue) {
					if (versionedProp.majorVersion == propToCheck.majorVersion
							&& versionedProp.minorVersion == propToCheck.minorVersion
							&& versionedProp.subminorVersion == propToCheck.subminorVersion) {
						return versionedProp.toString();
					}
				}

				if (propToCheck.isOkayForVersion(conn)) {
					if (propToCheck.preferredValue) {
						return propToCheck.toString();
					}

					versionedProp = propToCheck;
				} else {
					break;
				}
			}

			if (versionedProp != null) {
				return versionedProp.toString();
			}
		}

		return null;
	}

	final static int getNumberOfCharsetsConfigured() {
		return MYSQL_TO_JAVA_CHARSET_MAP.size() / 2; // because we UC every
														// key
	}

	final static boolean isAliasForSjis(String encoding) {
		return ("SJIS".equalsIgnoreCase(encoding)
				|| "WINDOWS-31J".equalsIgnoreCase(encoding)
				|| "MS932".equalsIgnoreCase(encoding)
				|| "SHIFT_JIS".equalsIgnoreCase(encoding) || "CP943"
				.equalsIgnoreCase(encoding));

	}

	final static boolean isMultibyteCharset(String javaEncodingName) {
		String javaEncodingNameUC = javaEncodingName
				.toUpperCase(Locale.ENGLISH);

		return MULTIBYTE_CHARSETS.containsKey(javaEncodingNameUC);
	}

	private static void populateMapWithKeyValuePairs(String configKey,
			Map mapToPopulate, boolean addVersionedProperties,
			boolean addUppercaseKeys) {
		String javaToMysqlConfig = CHARSET_CONFIG.getProperty(configKey);

		if (javaToMysqlConfig != null) {
			List mappings = StringUtils.split(javaToMysqlConfig, ",", true);

			if (mappings != null) {
				Iterator mappingsIter = mappings.iterator();

				while (mappingsIter.hasNext()) {
					String aMapping = (String) mappingsIter.next();

					List parsedPair = StringUtils.split(aMapping, "=", true);

					if (parsedPair.size() == 2) {
						String key = parsedPair.get(0).toString();
						String value = parsedPair.get(1).toString();

						if (addVersionedProperties) {
							List versionedProperties = (List) mapToPopulate
									.get(key);

							if (versionedProperties == null) {
								versionedProperties = new ArrayList();
								mapToPopulate.put(key, versionedProperties);
							}

							VersionedStringProperty verProp = new VersionedStringProperty(
									value);
							versionedProperties.add(verProp);

							if (addUppercaseKeys) {
								String keyUc = key.toUpperCase(Locale.ENGLISH);

								versionedProperties = (List) mapToPopulate
										.get(keyUc);

								if (versionedProperties == null) {
									versionedProperties = new ArrayList();
									mapToPopulate.put(keyUc,
											versionedProperties);
								}

								versionedProperties.add(verProp);
							}
						} else {
							mapToPopulate.put(key, value);

							if (addUppercaseKeys) {
								mapToPopulate.put(key
										.toUpperCase(Locale.ENGLISH), value);
							}
						}
					} else {
						throw new RuntimeException(
								"Syntax error in Charsets.properties "
										+ "resource for token \"" + aMapping
										+ "\".");
					}
				}
			} else {
				throw new RuntimeException("Missing/corrupt entry for \""
						+ configKey + "\" in Charsets.properties.");
			}
		} else {
			throw new RuntimeException("Could not find configuration value "
					+ "\"" + configKey + "\" in Charsets.properties resource");
		}
	}
}

class VersionedStringProperty {
	int majorVersion, minorVersion, subminorVersion;

	boolean preferredValue = false;

	String propertyInfo;

	VersionedStringProperty(String property) {
		property = property.trim();

		if (property.startsWith("*")) {
			property = property.substring(1);
			preferredValue = true;
		}

		if (property.startsWith(">")) {
			property = property.substring(1);

			int charPos = 0;

			for (charPos = 0; charPos < property.length(); charPos++) {
				char c = property.charAt(charPos);

				if (!Character.isWhitespace(c) && !Character.isDigit(c)
						&& c != '.') {
					break;
				}
			}

			String versionInfo = property.substring(0, charPos);
			List versionParts = StringUtils.split(versionInfo, ".", true);

			majorVersion = Integer.parseInt(versionParts.get(0).toString());

			if (versionParts.size() > 1) {
				minorVersion = Integer.parseInt(versionParts.get(1).toString());
			} else {
				minorVersion = 0;
			}

			if (versionParts.size() > 2) {
				subminorVersion = Integer.parseInt(versionParts.get(2)
						.toString());
			} else {
				subminorVersion = 0;
			}

			propertyInfo = property.substring(charPos);
		} else {
			majorVersion = minorVersion = subminorVersion = 0;
			propertyInfo = property;
		}
	}

	VersionedStringProperty(String property, int major, int minor, int subminor) {
		propertyInfo = property;
		majorVersion = major;
		minorVersion = minor;
		subminorVersion = subminor;
	}

	boolean isOkayForVersion(Connection conn) throws SQLException {
		return conn.versionMeetsMinimum(majorVersion, minorVersion,
				subminorVersion);
	}

	public String toString() {
		return propertyInfo;
	}
}
