<?php
class calssName
{
    public function __construct(){}
    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
	$user = $execution->workflow->myForm->getFieldValueByName('Field_0');
    $uid = $user[0]['uid'];
    $rid = $user[0]['rid'];
	$execution->setVariable('modirsetadi', '0');
	$us = MySQLAdapter::getInstance()->executeScalar("SELECT UserID FROM oa_depts_roles WHERE   RowID = $rid and level<=32 and level>0 and  `path` REGEXP  '3464|3465|3466|3467|3468|4670|3456|3536|3469|3470|3471|3472|3473|3468|3636'  LIMIT 1");
	if($us==$uid  )
	{
	$execution->setVariable('modirsetadi', '1');
	}



if(empty($uid)){
$acm = AccessControlManager::getInstance();
        $uid = $acm->getUserID(); }
		
		$name = MySQLAdapter::getInstance()->executeScalar("SELECT CONCAT(fname, ' ', lname) FROM oa_users WHERE UserID=$uid LIMIT 1");
		$gen = intval(MySQLAdapter::getInstance()->executeScalar("SELECT sex FROM oa_users WHERE UserID=$uid LIMIT 1"));
		
		$info = 'درخواست ماموريت ';
		if($gen == 1){
			$info = $info . ' آقای '; 
		}else{
			$info = $info . ' خانم ';
		}
		$info = $info . $name;
		MySQLAdapter::getInstance()->execute("UPDATE oa_document SET Subject = '$info' WHERE RowID = " . $execution->workflow->myForm->instanceID);


        $person = $execution->workflow->myForm->getFieldValueByName( 'Field_0');    
      
	 
         
         


			$execution->setVariable('timevalid', '1');			
		 
                $uid = $person[0]['uid'];
                //filerecorder::recorder("uidddddd=".var_export($person,true));
                $employeeID = MySQLAdapter::getInstance()->executeScalar("SELECT employeeID FROM oa_users WHERE UserID=$uid LIMIT 1"); 
                //filerecorder::recorder("uidddddd=".$employeeID);
    if(strlen($employeeID)>0){
    $execution->workflow->myForm->setFieldValueByName( 'Field_2', $employeeID);
	}
    }
}
?>