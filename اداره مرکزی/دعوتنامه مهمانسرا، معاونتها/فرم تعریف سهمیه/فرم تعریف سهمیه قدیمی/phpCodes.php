<?php

/*-------آیجکس مربوط به نمایش لیست--------*/
/*---guestHouseAccessList---*/



$db = MySQLAdapter::getInstance();
$acm = AccessControlManager::getInstance();
$uid = $acm->getUserID();

$permittedUsers = array(6685, 1662, 227, 2306);
if(!in_array($uid, $permittedUsers)){
    Response::getInstance()->response = 'access denied';
    return false;
}
$sql = "SELECT 
          dm.RowID,
          oa_depts_roles.UserID,
          oa_depts_roles.RowID,
          dm.Field_1 as dateStart,
          dm.Field_2 as dateEnd,
          IF(dm.Field_3 IS NULL,5,Field_3) as shareCount
        from  dm_datastoretable_1051 dm
        INNER JOIN oa_depts_roles on(oa_depts_roles.RowID = dm.Field_0)
        
        ";

$db->executeSelect($sql);

$body = '';
$i = 0;
while ($row = $db->fetchAssoc()){
    $shareCount = intval($row['shareCount']);
    $startDate = ($row['dateStart'] == '0000-00-00')?'':Date::GregToJalali($row['dateStart']);
    $dateEnd = ($row['dateEnd'] == '0000-00-00')?'':Date::GregToJalali($row['dateEnd']);
    $body .= '
    <tr id="accessRow_'.(++$i).'">
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$i.'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" id="userTD_'.$i.'" data-id="'.$row['UserID'].','.$row['RowID'].'"></td>
        <td style="padding: 2px;border: 1px solid #ccc;" >
            <input class="f-input" type="text"  id="shareCount_'.$i.'" value="'.$shareCount.'" style="width: 50px;font-size: 16px;" />
            </td>
        <td style="padding: 2px;border: 1px solid #ccc;"><div id="startDate_'.$i.'">'.$startDate.'</div></td>
        <td style="padding: 2px;border: 1px solid #ccc;"><div id="endDate_'.$i.'">'.$dateEnd.'</div></td>
        <td style="padding: 2px;border: 1px solid #ccc;"><img onclick="FormOnly.allFieldsContianer[0].removeRow('.$i.')" src="gfx/toolbar/cross.png" style="cursor: pointer;" /></td>
    </tr>
    ';
}

$html = '<table width="100%" class="f-table accessTable" cellpadding="0" cellspacing="1">     
            <tbody>
                <tr>
                    <th width="3%" style="padding: 2px; ">رديف</th>
                    <th width="40%" style="padding: 2px; ">كاربر</th>
                    <th width="5%" style="padding: 2px; ">سهميه</th>
                    <th width="20%" style="padding: 2px; ">از تاريخ</th>
                    <th width="20%" style="padding: 2px; ">تا تاريخ</th>
                    <th width="2%" style="padding: 2px; "></th>
                </tr>
                '.$body.'
            </tbody>
        </table>';
Response::getInstance()->response = $html;
/*--End guestHouseAccessList--*/

