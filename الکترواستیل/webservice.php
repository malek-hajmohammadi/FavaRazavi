<?php
try{
    $client = new SoapClient('http://89.165.40.173:99/webservice.asmx?wsdl');
    $param = array(
        'username' => 'c12e01f2a13ff5587e1e9e4aedb8242d',
        'password' => '2589c8fd53c9c33faee855837dd92918'
    );
    $res = $client->SelectRows($param);
    $res = $res->SelectRowsResponse;
}
catch(SoapFaultException $e){
    $res = $e;
}
catch(Exception $e){
    $res = $e;
}
//echo  $client->__getLastResponse();
$res = json_decode(json_encode($res), true);
//Response::getInstance()->response = print_r($res);
Response::getInstance()->response =var_export($res,true);

///////////////////////
try{
    $client = new SoapClient('http://10.139.57.22:89/WebService.asmx?wsdl');
    $param = array(
        'username' => 'c12e01f2a13ff5587e1e9e4aedb8242d',
        'password' => '2589c8fd53c9c33faee855837dd92918'
    );
    $res = $client->SelectRows($param);
    $res = $res->SelectRowsResponse;
}
catch(SoapFaultException $e){
    $res = $e;
}
catch(Exception $e){
    $res = $e;
}
//echo  $client->__getLastResponse();
$res = json_decode(json_encode($res), true);
//Response::getInstance()->response = print_r($res);
Response::getInstance()->response =var_export($res,true);

/////////////////
try{



    $client = new SoapClient('http://89.165.40.173:99/webservice.asmx?wsdl');

    $param = array(
        'username' => 'c12e01f2a13ff5587e1e9e4aedb8242d',
        'password' => '2589c8fd53c9c33faee855837dd92918'
    );
    $res = $client->SelectRows($param);
    //$res = $res->SelectRowsResponse;
}
catch(SoapFaultException $e){
    $res = $e;
}
catch(Exception $e){
    $res = $e;
}
echo  $client->__getLastResponse();
$res = json_decode(json_encode($res), true);
Response::getInstance()->response = print_r($res);
Response::getInstance()->response =var_export($res,true);
Response::getInstance()->response =$res;
///
///

try{
    //for testing in browser:$client = new SoapClient('http://89.165.40.173:99/webservice.asmx?wsdl',array("trace" => 1, "exception" => 1));
    $client = new SoapClient('http://10.139.57.57:99/WebService.asmx?wsdl',array("trace" => 1, "exception" => 1));
    $client->soap_defencoding = 'UTF-8';
    $res =$client->SelectRows();// __call("SelectRows",[]);
}
catch(SoapFault $e) {
    // if ($client)
    //$res = $client->__getLastResponse();

    // echo "<pre>SoapFault---: " . print_r($res, true) . "</pre>\n";
    echo "<pre>SoapFault: " . print_r($e, true) . "</pre>\n";
}

//echo var_export($res->SelectRowsResult,true);
if(!$res || !$res->SelectRowsResult) {
    echo "Ravan:There is a problem in this service.";
    return;
}

$res=$res->SelectRowsResult;
$res = json_decode($res, true);
$list=array();
for($i=0;$i<count($res);$i++){
    $instance=$res[$i]["PName"];
    $list[]=array($i,$instance);

}
Response::getInstance()->response =$list;

//


