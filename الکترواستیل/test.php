<?php
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
//echo  $client->__getLastResponse();
$res = json_decode(json_encode($res), true);
//Response::getInstance()->response = print_r($res);
//Response::getInstance()->response =var_export($res,true);
Response::getInstance()->response =$res;

//FromMrsAmoozgar
$res="malek";
try {
    $client = new SoapClient('http://10.139.57.57:99/webservice.asmx?wsdl', array("trace" => 1, "exception" => 1));
    $client->soap_defencoding = 'UTF-8';
    $res = $client->__call("SelectRows", []);
} catch (SoapFault $e) {
    $res = $client->__getLastResponse();
    echo "<pre>SoapFault---: " . print_r($res, true) . "</pre>\n";
    echo "<pre>SoapFault: " . print_r($e, true) . "</pre>\n";

}

Response::getInstance()->response =$res;



