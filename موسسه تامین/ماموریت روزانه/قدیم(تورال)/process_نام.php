<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
        $docID = $execution->workflow->myForm->instanceID;
        $Name = $execution->workflow->myForm->getFieldValueByName('Field_1');

        $newTitle = 'ماموریت روزانه (تامین) ' . $Name;
        $sql_title = "update oa_document set DocDesc='" . $newTitle . "',Subject='" . $newTitle . "' where RowID=" . $docID . "  limit 1";
        MySQLAdapter::getInstance()->execute($sql_title);

        //toprole=0 کاربر
        //toprole=1 ریس به بالا
        //toprole=99 اتمام

        $Status = $execution->workflow->myForm->getFieldValueByName('Field_8'); //وضعیت
        if ($Status == '') $execution->setVariable('toprole', 99);
    }
}

