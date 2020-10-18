<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $docID="123";
        $res=self::template($docID);
    }

    protected function template($docID)
    {
        $sql = "Malek";

        return $sql;
    }


}

