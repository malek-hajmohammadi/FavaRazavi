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
//Response::getInstance()->response = print_r($res);
//Response::getInstance()->response =var_export($res,true);
Response::getInstance()->response =$res;


