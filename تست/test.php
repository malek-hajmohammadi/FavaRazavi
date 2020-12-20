<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $execution->setVariable('Mafogh', 1);

        $Creator = $execution->workflow->myForm->getCreator();
        $UID = $Creator['uid'];
        $RID = $Creator['rid'];

        switch ($RID) {
            default:
                $execution->setVariable('Mafogh', 1);
                break;

// کوره پزیان
            case 12748:
                $execution->setVariable('Mafogh', 2);
                break;

// دلگشا خادر
            case 13104:
                $execution->setVariable('Mafogh', 2);
                break;

// نمایی قاسمی
            case 9837:
                $execution->setVariable('Mafogh', 2);
                break;

// حسن آرام
            case 8837:
                $execution->setVariable('Mafogh', 2);
                break;

// محمدیه ثانی
            case 9780:
                $execution->setVariable('Mafogh', 2);
                break;
//ناصر عسکری
            case 12344:
                $execution->setVariable('Mafogh', 2);
                break;
        }

//Set Subject
        $did = $execution->workflow->myForm->instanceID;
        $hoze = $execution->workflow->myForm->getFieldValueByName('Field_1');

        switch ($hoze)
        {
        case
            1: $hoze = 'ستاد بنياد كرامت'; break;
        case 2: $hoze = 'معاونت خدمات اجتماعي'; break;
        case 3: $hoze = 'موسسه جوانان و تشكلهاي انقلابي'; break;
        case 4: $hoze = 'موسسه موقوفه زائر'; break;
        case 5: $hoze = '‌‌‌‌‌‌‌‌موسسه تربيت بدني'; break;
        case 6: $hoze = 'مركز خادمياري و كانونهاي خدمت رضوي'; break;
        case 7: $hoze = 'مركز جامع مشاوره'; break;
        case 8: $hoze = 'مركز امور بانوان و خانواده'; break;
        }

        $subject = 'کاربرگ درخواست اعتبار - ' . $hoze;
        Document::setSubject($did, $subject);

    }
}
