<?php
$dateEnd = '1399/12/30';
$client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');

//$GID=$execution->workflow->myForm->getFieldValueByName('Field_18');
$GID='882866';
$s1 = "SELECT * FROM [Timex_TaminPoshtibani].adon.Kardex('".$GID."', '".$dateEnd."')";

$param = array(
    'username' => '3ef1b48067e4f2ac9913141d77e847dd',
    'password' => '9a3f5b14f1737c15e86680d9cd40b840',
    'objStr' => $s1
);
//$this->tempForQuery=$s1;

$res = $client->RunSelectQuery($param);
$res = $res->RunSelectQueryResult->cols;
$res = json_decode(json_encode($res), true);
$MandeMorkhasiString = urldecode($res['recs']['string'][30]);

$MandeMorkhasiAr=explode(':',$MandeMorkhasiString);

$mondehMorakhasiShow= $MandeMorkhasiAr[0].' روز و '.$MandeMorkhasiAr[1].' ساعت ';


Response::getInstance()->response=$mondehMorakhasiShow;




$remainMorakhasi="";
$dateEnd = '1399/12/30';
//$client = new SoapClient('http://10.10.10.25:9091/Timex.asmx?wsdl');
$client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
$GID='882866';
$s1 = "    SELECT * FROM [Timex_TaminPoshtibani].adon.Kardex('".$GID."', '".$dateEnd."')";
//[Timex_TaminPoshtibani].dbo
$param = array(
    'username' => '3ef1b48067e4f2ac9913141d77e847dd',
    'password' => '9a3f5b14f1737c15e86680d9cd40b840',
    'objStr' => $s1
);
$res = $client->RunSelectQuery($param);
$res = $res->RunSelectQueryResult->cols;
$res = json_decode(json_encode($res), true);
$MandeMorkhasiString = urldecode($res['recs']['string'][30]);

$MandeMorkhasiAr=explode(':',$MandeMorkhasiString);
$MandeMorkhasiShow=$MandeMorkhasiAr[0].' روز و '.$MandeMorkhasiAr[1].' ساعت ';
Response::getInstance()->response=$MandeMorkhasiShow;
