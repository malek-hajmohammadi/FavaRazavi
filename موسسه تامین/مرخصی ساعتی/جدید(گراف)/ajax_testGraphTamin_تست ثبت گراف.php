<?php

/*$client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
//$client = new SoapClient('http://172.16.61.253/Timex.asmx?wsdl');

$logId="990616T120512UW";
//YYmmDDTHHMMSSUW
$docID="123";

$st1 = "EXEC [Timex_TaminPoshtibani].[adon].[FlowDocs_Fill] '0', '".'15'."', '".'882866'."', '".'1399/06/16'.
    "', '".'1'."','".''."', '".'1399/05/15'."', '".'1399/05/15'."', ".'460'.", ".'620'.", ".'0'.", 1, 'WB".$docID."','WB".$docID."', '".$logId."', 1";

$st1 = "EXEC [Timex_TaminPoshtibani].[adon].[IOExplist_up] '882866', '".'15'."', '".'882866'."', '".'1399/06/16'.
    "', '".'1'."', '".''."', '".'1399/05/15'."', '".'1399/05/15'."', ".'460'.", ".'620'.", ".'0'.", 1, 'WB".$docID."','WB".$docID."', '".$logId."', 1";

$st1 = "EXEC [Timex_TaminPoshtibani].[adon].[IOData_ins] 882866,'1399/06/20',460,0,'500'";
//$st1 = "EXEC [adon].[IOData_ins] 882866, '1399/05/15', 650,15,'WB$docID';";

$sqlString = "EXEC [adon].[FlowDocs_Fill] '$reqId', '$formType', '$empId', '$docDate', '$tradodType', '$reqVal', '$startDate', '$endDate', $startTime,$endTime, $city, $docStatus, '$commentSys','$commentUser', '$log', $flag";
$reqIdOutput='';

//tested for morkhasi roozaneh OK
$st1 = "EXEC [Timex_TaminPoshtibani].[adon].[FlowDocs_Fill] '$reqIdOutput', 10, 882866, '1399/06/17',104 , '','1399/05/15','1399/05/15', 0,0, 0, 1, 'WB123','WB123', '990616T120512UW', 1";
$s ="EXEC [adon].[FlowDocs_Fill]                                        '', '10', 882866, '1399/06/22', 104, '', '1399/06/23', '1399/06/23', 0,0, 0, 1, 'WB13459454','WB13459454', '990622T1000UW', 1";
//morkhasi sati

$st1 = "EXEC [Timex_TaminPoshtibani].[adon].[FlowDocs_Fill] '$reqIdOutput', 15, 882866, '1399/06/17',1 , '','1399/05/16','1399/05/16',1260,1320, 0, 1, 'WB123','WB123', '990616T120512UW', 1";


$st1 = "EXEC [Timex_TaminPoshtibani].[adon].[IOData_ins] 33753,'1399/05/16',1260,0,'500'";


$client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
$s = "SELECT * FROM [Timex_TaminPoshtibani].[dbo].[fGetPersonInfo] ('0920365000','1399/01/01','1399/07/01')";
$param = array(
    'username' => '3ef1b48067e4f2ac9913141d77e847dd',
    'password' => '9a3f5b14f1737c15e86680d9cd40b840',
    'objStr' => $s
);
//$res = $client->RunSelectQuery($param);
//$res = $res->RunSelectQueryResult;*/




/*

$param = array(
    'username' => '3ef1b48067e4f2ac9913141d77e847dd',
    'password' => '9a3f5b14f1737c15e86680d9cd40b840',
    'objStr' => $st1
);
$res = $client->RunQuery($param);*/

/*$res = $res->RunQueryResult;
$res = json_decode(json_encode($res), true);*/
//Response::getInstance()->response =var_export($res,true);//."reqId=".$reqIdOutput;


//new for tested//
///////////////////////////////////////////////////////////test Morakhasi roozaneh
$client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
$s ="EXEC [Timex_TaminPoshtibani].[adon].[FlowDocs_Fill] '', '10', 882866, '1399/06/22', 104, '', '1399/06/23', '1399/06/23', 0,0, 0, 1, 'WB13459454','WB13459454', '990622T1000UW', 1";
$st1 = "EXEC [Timex_TaminPoshtibani].[adon].[IOData_ins] 33753,'1399/05/16',1260,0,'500'";

$param = array(
    'username' => '3ef1b48067e4f2ac9913141d77e847dd',
    'password' => '9a3f5b14f1737c15e86680d9cd40b840',
    'objStr' => $s
);
$res = $client->RunQuery($param);
$res = $res->RunQueryResult;
$res = json_decode(json_encode($res), true);
if($res)
    $result="real true"; //this was OK
else
    $result="not real true";
Response::getInstance()->response =$result;//."reqId=".$reqIdOutput;
//////////////////////////////////////////////////////////////end test morakhasi roozaneh
//////////////////////////////////////////////test Morakhasi Saati/////////////////////
$client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
$s ="EXEC [Timex_TaminPoshtibani].[adon].[FlowDocs_Fill] '', '10', 882866, '1399/06/22', 104, '', '1399/06/23', '1399/06/23', 0,0, 0, 1, 'WB13459454','WB13459454', '990622T1000UW', 1";
$df="EXEC [adon].[FlowDocs_Fill] '', '10', 882866, '1399/07/01', 104, '', '1399/06/31', '1399/07/02', 0,0, 0, 1, 'WB13518970','WB13518970', '990701T1000UW', 1";
$s="SELECT * FROM [Timex_TaminPoshtibani].adon.Kardex('882866', '1399/12/30')";

$param = array(
    'username' => '3ef1b48067e4f2ac9913141d77e847dd',
    'password' => '9a3f5b14f1737c15e86680d9cd40b840',
    'objStr' => $s
);
$res = $client->RunSelectQuery($param);
//$res = $res->RunQueryResult;
$res = json_decode(json_encode($res), true);
Response::getInstance()->response =$res;
if($res)
    $result="real true"; //this was OK
else
    $result="not real true";
Response::getInstance()->response =$result;//."reqId=".$reqIdOutput;
///delete from morakhasi saati
$sql .= "EXEC [adon].[IOData_del] " . $emp . ", '" . $date . "', " . $tt . ";";

//////////////////////////////////////insert/////////////////////
$client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
$st1 = "EXEC [Timex_TaminPoshtibani].[adon].[IOData_ins] 33753,'1399/06/26',1260,0,'5004'";

$param = array(
    'username' => '3ef1b48067e4f2ac9913141d77e847dd',
    'password' => '9a3f5b14f1737c15e86680d9cd40b840',
    'objStr' => $st1
);
$res = $client->RunQuery($param);

$res = $res->RunQueryResult;

$res = json_decode(json_encode($res), true);
if($res)
    $result="real true"; //this was OK
else
    $result="not real true";
Response::getInstance()->response =$result;
