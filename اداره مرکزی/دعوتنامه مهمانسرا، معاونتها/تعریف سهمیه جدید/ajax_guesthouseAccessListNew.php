<?php

$db = MySQLAdapter::getInstance();
$sql = "SELECT 
          dm.RowID,
          oa_depts_roles.UserID,
          oa_depts_roles.RowID as dRowId,
          dm.Field_1 as dateStart,
          dm.Field_2 as dateEnd,
          Field_3 as shareCount,
          dm.Field_4  as shareCountTashrif
          
        from  dm_datastoretable_1104 dm
        INNER JOIN oa_depts_roles on(oa_depts_roles.RowID = dm.Field_0)

        ";

$db->executeSelect($sql);
/*
Response::getInstance()->response = $db;
return;
*/

$body = '';
$i = 0;
while ($row = $db->fetchAssoc()) {
    $shareCount = intval($row['shareCount']);
    $shareCountTashrif = intval($row['shareCountTashrif']);


    $startDate = ($row['dateStart'] == '0000-00-00') ? '' : Date::GregToJalali($row['dateStart']);
    $dateEnd = ($row['dateEnd'] == '0000-00-00') ? '' : Date::GregToJalali($row['dateEnd']);
    $body .= '
    <tr id="dataTr_' . ($i) . '" >
        <td style="padding: 2px;border: 1px solid #ccc;" >' . ($i+1) . '</td>
        <td style="padding: 2px;border: 1px solid #ccc;" id="userTD_' . $i . '" data-id="' . $row['UserID'] . ',' . $row['dRowId'] . '"></td>
        <td style="padding: 2px;border: 1px solid #ccc;"><div id="startDate_' . $i . '">' . $startDate . '</div></td>
        <td style="padding: 2px;border: 1px solid #ccc;"><div id="endDate_' . $i . '">' . $dateEnd . '</div></td>
        <td style="padding: 2px;border: 1px solid #ccc;" >
            <input class="f-input" type="text" onInput="FormOnly.allFieldsContianer[0].changeSum(' . $i . ')" id="shareCount_' . $i . '" value="' . $shareCount . '" style="width: 40px;font-size: 16px;" />
            </td>
         <td style="padding: 2px;border: 1px solid #ccc;" >
            <input class="f-input" type="text" onInput="FormOnly.allFieldsContianer[0].changeSumTashrif(' . $i . ')" id="shareCountTashrif_' . $i . '" value="' . $shareCountTashrif . '" style="width: 40px;font-size: 16px;" />
            </td>   
        
        <td style="padding: 2px;border: 1px solid #ccc;" >
            <input class="f-input" readonly type="text" id="shareCountSum_' . $i . '" value="' . $shareCount . '" style="width: 40px;font-size: 16px;background-color: gainsboro;" />
            </td>
         <td style="padding: 2px;border: 1px solid #ccc;" >
            <input class="f-input" readonly type="text" id="shareCountTashrifSum_' . $i . '" value="' . $shareCountTashrif . '" style="width: 40px;font-size: 16px;background-color: gainsboro;" />
            </td>   
        <td style="padding: 2px;border: 1px solid #ccc;"><img onclick="FormOnly.allFieldsContianer[0].removeRow(' . $i . ')" src="gfx/toolbar/cross.png" style="cursor: pointer;" /></td>
    </tr>    ';
    $i++;
}
//
$html = '<table width="100%" class="f-table itemsTable" cellpadding="0" cellspacing="1">     
            <tbody>
                <tr>
        <th width="3%" rowspan="2" style="padding: 2px; ">رديف</th>
        <th width="40%" rowspan="2" style="padding: 2px; ">كاربر</th>
        <th width="40%" colspan="2" style="padding: 2px; ">دوره زماني</th>
        <th width="10%" colspan="2" style="padding: 2px; ">سهميه روزانه</th>
        
        <th width="10%" colspan="2" style="padding: 2px; ">كل سهميه در بازه</th>
        <th width="2%" rowspan="2" style="padding: 2px; "></th>


    </tr>
    <tr>


        <th width="15%" style="padding: 2px; ">از تاريخ</th>
        <th width="15%" style="padding: 2px; ">تا تاريخ</th>
        <th width="5%" style="padding: 2px; ">عادي</th>
        <th width="5%" style="padding: 2px; ">تشريفات</th>
        <th width="5%" style="padding: 2px; ">عادي</th>
        <th width="5%" style="padding: 2px; ">تشريفات</th>
        



    </tr>

                ' . $body . '
            </tbody>
        </table>';
Response::getInstance()->response = $html;



