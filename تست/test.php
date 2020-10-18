<?php

/*getting return value from compute field (php part)*/
class calssName
{
    public function execute($self)
    {

        $res = "
<button id='SearchButton' onclick='FormOnly.allFieldsContianer[7].search($uid)' onmousedown=''>جستجو
</button>";
        return array("res" => $res);
    }
}



/*seting global variable in workflow, process node*/
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        $execution->setVariable('statuslevel', 90);
    }




}

