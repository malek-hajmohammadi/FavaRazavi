<?php
      
     
 class calssName
{
    public function __construct(){}
    public function sendMessage($subject,$content,$referDesc,$fromUID,$fromRID,$ToUID,$ToRID){
		$rid=$fromRID;//from
		$uid=$fromUID;//from
		$start=1;
		$did = Document::Create($uid, $rid, $subject, 0, '', 2, 0, 1);
		TextContent::UpdateDocContent($did, $content, $start);
		$referId = DocRefer::ReferDraft($did, $uid, $rid, 0);
		$refer=$referId;
		$recs2=array (
		  0 => 
		  array (
			'type' => 0,
			'uid' => $ToUID,//to 
			'rid' => $ToRID,//to
			'oid' => 'null',
			'oname' => '',
			'iscc' => NULL,
			'isMultiSend' => '',
			'noteid' => '',
			'notedesc' => $referDesc,
		  )
		);
		$order_note='';
		$urg='';
		$timeout='';
		$secureF=0;
		$secureB=0;
		$track=0;
		$attachs=array ();
		$IsPerRefer='';
		DocRefer::MessageReferDocRefer($refer, $recs2, $order_note, $urg, $timeout, $secureF, $secureB, $track, $attachs,$IsPerRefer);
    }
    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
    $execution->setVariable('siagh', 0);
 //   $execution->setVariable('motghazi', 0);  //hajmohammadi - 981119 - dall form ict 111304888

///////كيوان////////
//$Debuger_uid=3689;
//$Debuger_rid=5365;
///////مرادي////////
$Debuger_uid=3754;
$Debuger_rid=5386;
///////////////////// 


        //$json['Person_Name'] = $execution->workflow->myForm->getFieldValueByName( 'Field_' );
        //$json['Person_Family'] = $execution->workflow->myForm->getFieldValueByName( 'Field_' );
        //$json['Person_Eshteghal'] = $execution->workflow->myForm->getFieldValueByName( 'Field_' );
        //$json['Date_EtmamGHarardad'] = $execution->workflow->myForm->getFieldValueByName( 'Field_' );
        //$json['Person_Madrak'] = $execution->workflow->myForm->getFieldValueByName( 'Field_' );
        $json['Personel_Identifier'] = $execution->workflow->myForm->getFieldValueByName( 'Field_2' );
if(strlen($json['Personel_Identifier'])>0)
 while(strlen($json['Personel_Identifier'])<5)
   $json['Personel_Identifier']='0'.$json['Personel_Identifier'];
$acm = AccessControlManager::getInstance();
$UID=$acm->getUserID();
$json['Karpardaz_Identifier'] = MySQLAdapter::getInstance()->executeScalar("SELECT employeeID FROM oa_users WHERE UserID=$UID LIMIT 1"); 

while(strlen($json['Karpardaz_Identifier'])<5)
   $json['Karpardaz_Identifier']='0'.$json['Karpardaz_Identifier'];

        //$json['Person_NationalCode'] = $execution->workflow->myForm->getFieldValueByName( 'Field_' );
        //$json['Person_NoeEstekhdam'] = $execution->workflow->myForm->getFieldValueByName( 'Field_' );
        //$json['Person_Org'] = $execution->workflow->myForm->getFieldValueByName( 'Field_' );
        //$json['Person_Post'] = $execution->workflow->myForm->getFieldValueByName( 'Field_' );
        //$json['Person_Shoroobekar'] = $execution->workflow->myForm->getFieldValueByName( 'Field_' );
        $json['MozueMamoriat'] = $execution->workflow->myForm->getFieldValueByName( 'Field_8' );
        $json['Execution_FromDate'] = $execution->workflow->myForm->getFieldValueByName( 'Field_3' );
        $json['Execution_ToDate'] = $execution->workflow->myForm->getFieldValueByName( 'Field_4' );
        $json['MabdaMamoriat'] = $execution->workflow->myForm->getFieldValueByName( 'Field_39' );
        $json['MaghsadMamoriat'] = $execution->workflow->myForm->getFieldValueByName( 'Field_40' );

        $json['RAVAN_DOCID'] = $execution->workflow->myForm->instanceID;
        $json['RAVAN_REGCODE'] = $execution->workflow->myForm->getFieldValueByName( 'Field_27' );
        
        //$json['post'] = $execution->workflow->myForm->getFieldValueByName( 'Field_' );
        $json['Masafat'] = $execution->workflow->myForm->getFieldValueByName( 'Field_41' );
        //$json['C_DocStatus_ID'] = $execution->workflow->myForm->getFieldValueByName( 'Field_' );
        $json['KharejAzKeshvar'] = $execution->workflow->myForm->getFieldValueByName( 'Field_24' );
        $json['SherkatDarHamayesh'] = $execution->workflow->myForm->getFieldValueByName( 'Field_25' );
        $json['NoeMamoriat'] = $execution->workflow->myForm->getFieldValueByName( 'Field_6' );
        $json['MahalEghamat'] = $execution->workflow->myForm->getFieldValueByName( 'Field_10' );
        $json['Description'] = '';
		$details=false;
		$DocID = $execution->workflow->myForm->instanceID;
		$db = MySQLAdapter::getInstance();
		$sql="SELECT dm_datastoretable_872.* FROM `dm_datastoretable_872` left outer join oa_document  on dm_datastoretable_872.docID= oa_document.rowid  where `MasterID`=$DocID and oa_document.isEnable=1 order by dm_datastoretable_872.docID asc";
		$ress= $db->executeSelect($sql);
		while($row=$db->fetchAssoc()){
			$details[]= $row;
		}//end while
		if(is_array($details ))
		{
			foreach($details as $i=>$d)
			{
				$json['details'][]=array("RaftVaBargasht"=>(intval($d['Field_0'])+1), "NoeVasile"=>(intval($d['Field_1'])+1),"DateKharid"=>$d['Field_2'],"Mablagh"=>$d['Field_3'],"SherkatMosaferati"=>$d['Field_4']);
			}
		}

try{
foreach($json as $i=>$v){
			if(empty($v) && 1==2)
				$json[$i]='NULL';
		}
ini_set('soap.wsdl_cache_enabled',0);
ini_set('soap.wsdl_cache_ttl',0);
$param=array('username'=>'siagh','password'=>'siagh1256','JSonParam'=>'['.json_encode($json).']');

/*
//-------------كد تست شده با نسخه پي اچ پي قبلي  كه درست بوده---------------------
$client = new nusoap_client('http://10.10.10.223/esb/support?wsdl');   
$client->soap_defencoding = 'UTF-8';
$client->decode_utf8 = false;
$resp1= $client->call('Support_master.setMamoriat', $param);
*/

$client = new SoapClient('http://10.10.10.223/esb/support?wsdl');  
$client->soap_defencoding = 'UTF-8';
$client->decode_utf8 = false;
$resp1 = $client->__soapCall('Support_master.setMamoriat', $param);
$respArr=json_decode($resp1 , true);

if($client->fault){
    $execution->workflow->myForm->setFieldValueByName( 'Field_45',('Error:fault:'.var_export($client->fault,true)) );
    $execution->setVariable('siagh', 0);
}else{
    
    //$execution->setVariable('siagh', intval($resp1));   
	preg_match('/\d+/', ($respArr['message'].'_0'), $matches); 
	$execution->setVariable('siagh', intval($matches[0]));   
	if(intval($matches[0])>0)
	{
		$execution->workflow->myForm->setFieldValueByName( 'Field_45', $respArr['message']  );
	}
	else
	{
		$execution->workflow->myForm->setFieldValueByName( 'Field_45', 'Error:'.$respArr['message']  );
	}
}


		//$resp1=$client->setMamoriat($param);
		//$resp1=$client->Support_master->setMamoriat($param);
                //$resp1=$client->Support_master.setMamoriat('siagh','siagh1256' , json_encode($json));
                //$resp1=$client->setMamoriat('siagh','siagh1256' , json_encode($json));
               // $resp1=$client->__getFunctions();
                // $resp1= $client->'Support_master.setMamoriat'($param);
//-------------------
/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://10.10.10.223/esb/index.php/support/setMamoriat");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
            "username=siagh&password=siagh1256&JSonParam=".json_encode($json));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$resp1 = curl_exec ($ch);
curl_close ($ch);
*/
//----------------------
		//$execution->workflow->myForm->setFieldValueByName( 'Field_20','resp:'.var_export($resp1,true) );
} catch(Exception $er){
			$m=$er->getMessage();
			$execution->workflow->myForm->setFieldValueByName( 'Field_45','Error:catched:'.$m );
                        $execution->setVariable('siagh', 0);
}	
  }//end func
}
 
 
     
 
 
 
 ?>
