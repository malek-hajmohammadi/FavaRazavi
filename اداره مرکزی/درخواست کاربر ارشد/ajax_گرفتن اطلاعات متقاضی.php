<?php

$db = MySQLAdapter::getInstance();
$UID = Request::getInstance()->varCleanFromInput('UID');
$RID = Request::getInstance()->varCleanFromInput('RID');
//$ACM = AccessControlManager::getInstance();
//$UID = $ACM->getUserID();

// $UID = 1648;
// $RID = 2580;
$sql = "SELECT * FROM oa_users WHERE UserID = ".$UID;
$db->executeSelect($sql);
$person = $db->fetchAssoc();
if($person['sex'] == '1') $sex = 'آقاي';
else $sex = 'خانم';
$Name = $sex.' '.$person['fname'].' '.$person['lname'];
$Emp = $person['employeeID'] != NULL ? $person['employeeID'] : '-';
$Username = $person['OldUserID'];
$Tel = $person['tel'] != NULL ? $person['tel'] : '';
$InTel = $person['internalTel'] != NULL ? $person['internalTel'] : '';
$Mobile = $person['mobile'] != NULL ? $person['mobile'] : '-';
$CodeM = $person['NationalCode'] != NULL ? $person['NationalCode'] : '-';

//header("Content-type: image/jpg");
//$Pic = $person['picture'] != NULL ? $person['picture'] : '';

$Role="";
if (true)//find a default role of user
{
    //$ACM = AccessControlManager::getInstance();

    if (Chart::getDefaultRoleID($UID)!=$RID)/*it is'nt real user. it is an assistance*/ {

        $RID = Chart::getDefaultRoleID($UID);
        $Role = Chart::GetRoleName($RID);

        $representationRole =$RID;
        $representation = Chart::GetRoleName($representationRole);

    } else {

        $Role = Chart::GetRoleName($RID);
        $representation = "";
    }


}
 $Unit = Chart::getUnitName($RID);


//$sql = "SELECT Name FROM oa_depts_roles WHERE RowID = ".$RID." LIMIT 0,1";
//$Role = $db->executeScalar($sql);

$res = array(
    'Name' => $Name,
    'Role' => $Role,
    'representation'=>$representation,
    'Emp' => $Emp,
    'Username' => $Username,
    'Tel' => $Tel,
    'InTel' => $InTel,
    'Mobile' => $Mobile,
    'CodeM' => $CodeM,
    'Unit' => $Unit,
    //'Pic' => $Pic
);
$res = json_encode($res,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

Response::getInstance()->response = $res;
