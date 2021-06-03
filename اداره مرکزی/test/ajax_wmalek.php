<?php


class MainAjax
{


    public function main()
    {
        $output = "";
        $output=$this->getCountMorkhasiRoozaneh("1400/03/01","1400/03/30","34018");
        return $output;

    }

    private function getCountMorkhasiRoozaneh($dateShamsiStart,$dateShamsiEnd,$pid)
    {
        $dateMiladiStart = Date::JalaliToGreg($dateShamsiStart);
        $dateMiladiEnd=Date::JalaliToGreg($dateShamsiEnd);

        $sqlcheck = "SELECT count( `RowID` )
 FROM `dm_datastoretable_790` 
 WHERE `Field_2` >= '" . $dateMiladiStart . "' AND `Field_2` <= '" . $dateMiladiEnd."' AND `Field_1` = '" . $pid."'";

        $tedad = WFPDOAdapter::getInstance()->executeScalar($sqlcheck);
        return $tedad;

    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;


