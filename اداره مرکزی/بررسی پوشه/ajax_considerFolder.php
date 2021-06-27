<?php


class MainAjax
{


    public function main()
    {



        $output = "";
        $output= $this->showFiles();
        //$output=$this->isDirExist();
        return $output;

    }

    private function showFiles()
    {


        $stringOut="";
        //$fileList = glob('/opt/storage/Ahkam/Morkhasi/NewCsv/*');
        //$fileList = glob('/mnt/ahkam/Morkhasi/*');
        $fileList = glob('/opt/storage/Morkhasi/*');

        foreach ($fileList as $filename) {
            if (is_file($filename)) {
                $stringOut.= $filename. '<br>';
            }
        }
        return $stringOut;

    }
    private function isDirExist(){
        //$pathst="/mnt/fish/";
        $pathst='/opt/storage/Ahkam/Morkhasi/NewCsv/';
        $res=is_dir($pathst);
        return $res;
    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;






