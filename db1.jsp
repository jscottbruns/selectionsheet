<html> 
<head> 
<basefont face="Arial"> 
</head> 

<body> 
<center> 
<% 
// check submit state 
String submit = request.getParameter("submit"); 

// form not yet submitted 
// display initial page 
if(submit == null) 
{ 
%> 

<form action="db1.jsp" method="GET"> 
Enter your name:&nbsp; 
<input type="text" name="name" size="10"> &nbsp; <input  
type="submit" name="submit" value="Go"> </form> 

<% 
} 
// form submitted, display result 
else 
{ 
%> 

<%@ page language="java" import="java.sql.*" %> 

<% 
// get username 
String uid = request.getParameter("name"); 

// define database parameters 
String host="localhost"; 
String user="ss_client"; 
String pass="4600powder_tb"; 
String db="selectionsheet"; 
String conn; 
%> 

<h2><% out.println(uid); %>'s Little Black Book</h2> 
<hr> 

<table border=1 cellspacing=4 cellpadding=4> 
<tr> 
<td><b>First name</b></td> 
<td><b>Last name</b></td> 
<td><b>Tel</b></td> 
<td><b>Fax</b></td> 
<td><b>Email address</b></td> 
</tr> 

<% 
Class.forName("com.mysql.jdbc.Driver"); 

// create connection string 
conn = "jdbc:mysql://" + host + "/" + db + "?user=" + user  
+ "&password=" + pass; 

// pass database parameters to JDBC driver 
Connection Conn = DriverManager.getConnection(conn); 

// query statement 
Statement SQLStatement = Conn.createStatement(); 

// generate query 
String Query = "SELECT * FROM lots"; 

// get result 
ResultSet SQLResult = SQLStatement.executeQuery(Query); 

// display records 
// if available 
 while(SQLResult.next()) 
 { 
   String FName = SQLResult.getString("obj_id"); 
   String LName = SQLResult.getString("lot_no"); 
   String Tel = SQLResult.getString("lot_hash"); 

   out.println("<tr><td>" + FName + "</td><td>" +  
LName + "</td><td>" + Tel + "</td></tr>"); 
 } 
// close connections 
SQLResult.close(); 
SQLStatement.close(); 
Conn.close(); 

} 
%> 

</table> 
</center> 
</body> 
</html>


