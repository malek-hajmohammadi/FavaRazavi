<?php

class MainAjax
{


    public function main()
    {
        $output="";
        $output=$this->getRemainCredit();
        return $output;

    }

    protected function getRemainCredit(){
        $result="0";

        try {

            $client = new SoapClient("http://192.168.0.121/WSStaffInOut/StaffInOut.asmx?wsdl");
            $client->soap_defencoding = 'UTF-8';
            $params = array("uname" => 'bf6db9a036816352f2a5128417ad3154', "pass" => 'dbe99b52bf0008708dc38373490d1526',
                            "Xcode" => 196);

            $result = $client->GetCustomer($params);

            $result=$result->GetCustomerResult->data->Customer->GBes;
            //$result=$result->GetCustomerResult->data->Customer;



            // $res = get_object_vars($res->Xname)["StaffOutIn"];


        } catch (SoapFaultException $e) {
            $result=$e;
        } catch (Exception $e) {
            $result= $e;
        }

        return $result;

    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;





