<?php
class MainAjax
{


    public function main()
    {
        $output="";
        $output=$this->graphQueryTest();
        return $output;

    }

    private function graphQueryTest(){

        $client = new SoapClient('http://192.168.100.46:85/TimeX.asmx?wsdl');
        $query="Query: EXEC [adon].[IOMnuList_Fill] 360594, '1400/03/04','1400/02/25','1400/02/25', 'ALL', 80, 36, 1, '000304T000000UW', 'Form-Ez-35826';".
            "EXEC [adon].[IOMnuList_Fill] 360594, '1400/03/04','1400/02/25','1400/02/25', 'ALL', 85, 36, 1, '000304T000000UW', 'Form-Ez-35826';".
            "EXEC [adon].[IOMnuList_Fill] 360594, '1400/03/04','1400/02/25','1400/02/25', 'ALL', 90, 36, 1, '000304T000000UW', 'Form-Ez-35826'; - res: TrueTruesuccess";
        $param = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr'   => $query
        );
        $res = $client->RunQuery($param);
        $res = $res->RunQueryResult;
        $res = json_decode(json_encode($res), true);
        return $res;


    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;


