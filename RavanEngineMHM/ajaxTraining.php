<?php
///////////////////////حالت جدید که با کلاس هست
class MainAjax
{
    private function getDataFromDatabase()
    {
        $list = [];
        $list[0][0] = "خمیرمایه نوع یک";
        $list[0][1] = "کیلو";
        $list[0][2] = "120000";
        $list[0][3]="200";
        $list[0][3]="70000";

        return $list;
    }

    public function makeHtml()
    {
        $dataFromDatabase = $this->getDataFromDatabase();
        $endHtml = $this->style() . $this->header() . $this->body($dataFromDatabase) .$this->addButton(). $this->footer();
        return $endHtml;
    }



    private function addButton(){
        $addBtn = "
    <tr>
            <td style=\"padding: 2px;padding-top: 7px;
        padding-bottom: 7px;border: 1px solid #ccc;background-color: #c5e1a5;\"><img onclick=\"FormView.codeSet.DetailedTable.addRow()\"
                                                                  src=\"gfx/toolbar/plus.png\" style=\"cursor: pointer;\"/></td>
            </tr>";

        return $addBtn;


    }

    private function footer()
    {
        $footer = "";
        $footer = "</tbody> </table>";
        return $footer;
    }
}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->makeHtml();
return $mainAjax;































/////////////////////////

//دریافت پارامتر از بیرون که بهتره اول چک کنیم که چنین مقداری در فراخوانی هست یا نه//
if (Request::getInstance()->varCleanFromInput('placeHolder'))
    $varTemp = Request::getInstance()->varCleanFromInput('placeHolder');
else {

    Response::getInstance()->response = "There is no specefic input";
    return;
}

//ارسال خروجی به بیرون//

$a="PHP";

Response::getInstance()->response=$a;

//آرایه ها و لیست ها رو هم به همین صورت می دیم به خروجی ، که آنطرف بصورت آبجکتی از json می شه استفاده کرد//

