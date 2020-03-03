<?php

class calssName
{
    public function execute($self)
    {

        $res = "<button id='SearchButton' onclick='FormView.myForm.getItemByName(\"Field_0\").LoadJS()'>جستجو</button>";
        return array("res" => $res);
    }
}
