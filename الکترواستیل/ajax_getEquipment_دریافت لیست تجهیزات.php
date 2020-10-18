<?php

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





