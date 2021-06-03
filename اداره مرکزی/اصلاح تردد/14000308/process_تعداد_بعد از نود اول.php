<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        /*Insert Code Here*/
        $this->considerCountEslahTaradod($execution);

    }
    private function considerCountEslahTaradod(ezcWorkflowExecution $execution){


        $date = $execution->workflow->myForm->getFieldValueByName('Field_2');

        $dateArray = explode('/', $date);
        $dateShamsiStart=$dateArray[0].'/'.$dateArray[1].'01';

        if($dateArray[1]<=6)
          $dateShamsiEnd=$dateArray[0].'/'.$dateArray[1].'31';
        else
            $dateShamsiEnd=$dateArray[0].'/'.$dateArray[1].'30';

        $pid = $execution->workflow->myForm->getFieldValueByName('Field_1');


        $count=$this->getCountEslahTaradod($dateShamsiStart,$dateShamsiEnd,$pid);

        $execution->setVariable('countEslahTaradod',  $count);

    }
    private function getCountEslahTaradod($dateShamsiStart,$dateShamsiEnd,$pid)
    {
        $dateMiladiStart = Date::JalaliToGreg($dateShamsiStart);
        $dateMiladiEnd=Date::JalaliToGreg($dateShamsiEnd);

        $sqlcheck = "SELECT count( `RowID` )
 FROM `dm_datastoretable_790` 
 WHERE `Field_2` >= '" . $dateMiladiStart . "' AND `Field_2` <= '" . $dateMiladiEnd."' AND `Field_1` = '" . $pid."'";

        $count = WFPDOAdapter::getInstance()->executeScalar($sqlcheck);
        return $count;

    }


}

