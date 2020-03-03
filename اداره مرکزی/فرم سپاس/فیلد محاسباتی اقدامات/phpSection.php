<?php

/*------------------------------*/

class calssName
{
    public function execute($self)
    {
        $db = MySQLAdapter::getInstance();
        $sql = "SELECT       dm.*,   concat(oa_users.fname,' ',oa_users.lname) as user,  oa_depts_roles.Name as roleName  
          FROM dm_datastoretable_1097 dm
           inner join oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable= 1)
           left join vi_form_userrole on(vi_form_userrole.docID = dm.DocID and FieldName = 'Field_2') 
           left join oa_users on (oa_users.UserID = vi_form_userrole.uid)
           left join oa_depts_roles on (oa_depts_roles.RowID = vi_form_userrole.rid) 
           where (dm.Field_3 = 1 or dm.Field_3 is null) and MasterID = " . $self->docid;
        $html = "
<tr>
    <th>رديف</th>
    <th>شرح اقدام</th>
    <th>تاريخ اقدام</th>
    <th width='10%'>اقدام كننده</th>
</tr>";
        $acm = AccessControlManager::getInstance();
        $rid = $acm->getRoleID();
        $db->executeSelect($sql);
        $i = '';
        $permittedRoles = array(4351, 8920, 1508, 11904, 11905, 11895);
        while ($row = $db->fetchAssoc()) {
            $i .= '0';
            $html .= '
<tr> ';
            $html .= '
    <td>' . strlen($i) . '</td>
    ';
            $html .= '
    <td>' . $row['Field_0'];

                $html .= '
        <button class="removeButton"
                onclick="FormView.myForm.getItemByName(\'Field_28\').removeAction(' . $row['RowID'] . ', this)"
                style="padding-left: 10px;padding-right: 10px;float: left " title="حذف اقدام">حذف اين اقدام<img
                    style="vertical-align:middle;margin-bottom: -6px;margin-right: 10px;" src="gfx/toolbar/delete.png"
                    align="middle"></button>
        ';

            $html .= '
    </td>
    ';
            $date = explode(' ', $row['Field_1']);
            $date = Date::GregToJalali($date[0]) . ' ' . $date[1];
            $html .= '
    <td dir="ltr">' . $date . '</td>
    ';
            $html .= '
    <td>' . $row['user'] . ' (' . $row['roleName'] . ')</td>
    ';
            $html .= '
</tr>';
        }
        $html = '
<style>             #FORMVIEW-FORM-MAINDIV textarea {
        width: 100% !important;
        border: #ccc 1px solid
    }

    .pers_report * {
        text-align: center;
        border-collapse: collapse;
        font-family: b nazanin, nazanin;
    }

    .pers_report li {
        margin-right: 10px;
        text-align: right;
        font-size: 14px;
    }

    .pers_report span {
        cursor: pointer;
    }

    .pers_report th {
        background-color: #ccc;
        font-size: 13px;
    }

    .pers_report td {
        font-size: 17px;
    }         </style>
<div style="background-color:white; " class="pers_report" id="listtashvighat">
    <center>
        <table cellpadding="1" cellspacing="0" border="1" width="100%" align="center">' . $html . '</table>
    </center>
</div>';
        return array("res" => $html);
    }
}
