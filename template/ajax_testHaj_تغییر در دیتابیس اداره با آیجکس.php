<?php
$sqlSt="UPDATE `dm_datastoretable_1388` SET `Field_23` = 'مجمع عمومی فوق العاده شرکت کشت و صنعت جوین' WHERE `dm_datastoretable_1388`.`RowID` = 507";
$db = PDOAdapter::getInstance();
$res = $db->executeScalar($sqlSt);

