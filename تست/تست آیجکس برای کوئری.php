<?php
$sql="SELECT 
					oa_depts_roles.RowID AS RoleID,
				  	oa_depts_roles.RowType,
					oa_users.UserID,
					oa_users.fname,
					oa_users.lname,
					oa_depts_roles.Name,
					oa_depts_roles.IsDefault
			  FROM
				oa_depts_roles
				INNER JOIN oa_users ON (oa_depts_roles.UserID=oa_users.UserID AND oa_users.IsEnable = 1 AND oa_depts_roles.RowType = 1)
			  WHERE  path LIKE :deptID";

$db = WFPDOAdapter::getInstance();



$db->execute($sql);

