<?php
//ثبت مرخصی ساعتی از ساعت 8 تا 10//
$s="EXEC [adon].[IOData_ins] EMPID, '13990504','480', 1";
$s="EXEC [adon].[IOData_ins] EMPID, '13990504','600', 0";

//مرخصی روزانه از 13990501 الی 13990504//
$s="EXEC [adon].[IOData_ins] EMPID, '13990501', 1";
$s="EXEC [adon].[IOData_ins] EMPID, '13990502', 1";
$s="EXEC [adon].[IOData_ins] EMPID, '13990503', 1";
$s="EXEC [adon].[IOData_ins] EMPID, '13990504', 1";

//دستور Delete//
$s="EXEC [adon].[IOData_del] EMPID,'13990504','480'";

