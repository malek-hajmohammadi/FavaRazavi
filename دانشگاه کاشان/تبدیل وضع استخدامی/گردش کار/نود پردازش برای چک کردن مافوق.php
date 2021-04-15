<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $res=$this->haveSuperior($execution);

        $execution->setVariable('haveSuperior',$res);

    }

    protected function haveSuperior($execution){
        $user = $execution->workflow->myForm->getFieldValueByName('Field_7');
        $uid = $user[0]['uid'];
        $rid = $user[0]['rid'];

        if($uid==""){
            return 0;
        }

        return 1;

    }

}

