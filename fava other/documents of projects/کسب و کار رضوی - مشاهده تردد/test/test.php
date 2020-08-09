<?php
try{	
		$params = array('location' => "http://46.209.31.219:8082/WSStaffInOut/StaffInOut.asmx?wsdl");
		$client = new SoapClient("http://46.209.31.219:8082/WSStaffInOut/StaffInOut.asmx?wsdl",$params );		
		$client->soap_defencoding = 'UTF-8';
		$params=array("uname"=>'bf6db9a036816352f2a5128417ad3154',"pass"=>'dbe99b52bf0008708dc38373490d1526', "userID"=>'101',"startDate"=>'1399/05/01',"endDate"=>'1399/05/18' );
		//$params=array("uname"=>'fava',"pass"=>'Adminkkr', "userID"=>'101',"startDate"=>'',"endDate"=>'' );
		$res = $client->GetStaffInOut($params)->GetStaffInOutResult;
		echo 'result::'.$res->istrue."---";
		echo 'Desc::'.$res->Desc."---";		
		$res2= get_object_vars($res->data)["StaffOutIn"];
		//echo count($res2);
		echo 'Data::'.var_export(get_object_vars($res2[1]),true);
} catch (Exception $e) {
	echo '666'.$e->getMessage();
}



?>