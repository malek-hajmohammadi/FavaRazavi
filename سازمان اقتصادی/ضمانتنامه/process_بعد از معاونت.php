<?php


class calssName
{
    public function __construct(){}
    public function execute(ezcWorkflowExecution $execution)
    {

        $db = MySQLAdapter::getInstance();

        $acm = AccessControlManager::getInstance();
        $rid = $acm->getRoleID();
        $uid = $acm->getUserID();




       $formDocID = $execution->workflow->myForm->instanceID; //شماره فرم رو برمی گردونه//
        //یک الگوی نامه درست می کنم که عنوانش رو انگلیسی می گذارم و در کوئری پایین می گذارم//

        $sql = "SELECT * FROM `oa_per_letter_template` where Title = 'zemanatnamehBanki'";
        $db->executeSelect($sql);
        $template =$db->fetchAssoc();

        $html = $template['Content'];
        //$subject = $template['Subject'];
        //موضوع نامه در کارتابل است//
        //$docDesc = $template['Note']; //چکیده نامه//


        $bank=$execution->workflow->myForm->getFieldValueByName( 'Field_24');
        $shobeh=$execution->workflow->myForm->getFieldValueByName( 'Field_26');
        $company=$execution->workflow->myForm->getFieldValueByName( 'Field_0');
        $body=$execution->workflow->myForm->getFieldValueByName( 'Field_30');


        $html = str_replace('bank', $bank, $html);
        $html = str_replace('shobeh', $shobeh, $html);
        $html = str_replace('company', $company, $html);
        $html = str_replace('body', $body, $html);


        $subject = "درخواست ضمانتنامه بانکی شرکت ".$company;

        $docDesc = $subject;






        $did = Document::Create($uid, $rid, $subject, 1, $docDesc, 1, 0, 1);
        ////

        $lid = Letter::Create( $did, 2,1); //فقط یک نامه ثبت می شه//

        $referID = DocRefer::ReferDraft($did, $uid, $rid, 0);//نامه صاحب پیدا می کند//

        TextContent::UpdateDocContent($did, $html);//متن نامه رو تغییر می ده//



        $receivers=array();
        $receivers[0]=array();
        $receivers[0]['type'] = 2; //

        /*
         * uid modir amel 1636
         * rid 2296
         */
        $receivers[0]['uid'] = 1;
        $receivers[0]['rid'] = 666;
        $receivers[0]['oid'] = 'null';
        $receivers[0]['oname'] = '';
        $receivers[0]['iscc'] = 0;  ///رو نوشت باشه یه/
        /// 1 باشه رونوشت
        /// 0 ارجاع معمولی
        DocRefer::ReferDocRefer($referID, $receivers, 'احتراما جهت استحضار');





        // Letter::SetSigners( $lid,  $uid.','.$rid);//محل امضاء درست می کند////در اینجا نمی خواهیم نامه امضاء بشه//
        DocLink::AddLink(null, $did, $rid, $template['Subject'], 1, $formDocID, null); //پیرو می کنه به فرم//

       // Letter::Sign($referID);
        //نامه وقتی امضا می شه با این دستور مستقیم می ره به دبیرخانه//




    }
}