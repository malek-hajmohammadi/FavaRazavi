<?php

$en_files = scandir('en_us');
$en_settings_files = scandir('en_us/Settings');

$fa_files = scandir('fa_ir');
$fa_settings_files = scandir('fa_ir/Settings');

$index = 0;
foreach ($en_files as $file) {
    if(strpos($file, '.php') > 0) {
        if(!file_exists('fa_ir/'.$file)) {
            echo "<br> the file $file doesn't exist in fa_ir";
            continue;
        }
        $en_languageStrings = [];
        $en_jsLanguageStrings = [];
        $fa_languageStrings = [];
        $fa_jsLanguageStrings = [];
        unset($languageStrings);
        unset($jsLanguageStrings);

        require_once 'en_us/'.$file;
        if(isset($languageStrings)) {
            $en_languageStrings = $languageStrings;
        }
        if(isset($jsLanguageStrings)) {
            $en_jsLanguageStrings = $jsLanguageStrings;
        }

        unset($languageStrings);
        unset($jsLanguageStrings);

        require_once 'fa_ir/'.$file;
        if(isset($languageStrings)) {
            $fa_languageStrings = $languageStrings;
        }
        if(isset($jsLanguageStrings)) {
            $fa_jsLanguageStrings = $jsLanguageStrings;
        }

        $diff = [];
        $js_diff = [];

        foreach ($en_languageStrings as $key => $value) {
            if(!array_key_exists($key, $fa_languageStrings)) {
                $diff[$key]= $value;
            }
        }
        foreach ($en_jsLanguageStrings as $key => $value) {
            if(!array_key_exists($key, $fa_jsLanguageStrings)) {
                $js_diff[$key] = $value;
            }
        }
        echo "<hr><div style='color: green'> ".(++$index).": processing the file $file</div>";
        $subIndex = 0;
        if(count($diff) > 0) {
            foreach ($diff as $key=>$value) {
                echo "<div style='margin-left: 10px'>".(++$subIndex).": the item <b style='color: blue'>$key = $value</b> is not exist in fa_ir</div>";
            }
        }

        if(count($js_diff) > 0) {
            foreach ($js_diff as $value) {
                echo "<div style='margin-left: 10px'>".(++$subIndex).": the item <b style='color: blue'>$key = $value</b> is not exist in fa_ir</div>";
            }
        }
    }
}

foreach ($en_settings_files as $file) {
    if(strpos($file, '.php') > 0) {
        if(!file_exists('fa_ir/Settings/'.$file)) {
            echo "<br> the file Settings/$file doesn't exist in fa_ir";
            continue;
        }
        $en_languageStrings = [];
        $en_jsLanguageStrings = [];
        $fa_languageStrings = [];
        $fa_jsLanguageStrings = [];
        unset($languageStrings);
        unset($jsLanguageStrings);

        require_once 'en_us/Settings/'.$file;
        if(isset($languageStrings)) {
            $en_languageStrings = $languageStrings;
        }
        if(isset($jsLanguageStrings)) {
            $en_jsLanguageStrings = $jsLanguageStrings;
        }

        unset($languageStrings);
        unset($jsLanguageStrings);

        require_once 'fa_ir/Settings/'.$file;
        if(isset($languageStrings)) {
            $fa_languageStrings = $languageStrings;
        }
        if(isset($jsLanguageStrings)) {
            $fa_jsLanguageStrings = $jsLanguageStrings;
        }

        $diff = [];
        $js_diff = [];

        foreach ($en_languageStrings as $key => $value) {
            if(!array_key_exists($key, $fa_languageStrings)) {
                $diff[] = $key;
            }
        }
        foreach ($en_jsLanguageStrings as $key => $value) {
            if(!array_key_exists($key, $fa_jsLanguageStrings)) {
                $js_diff[] = $key;
            }
        }
        echo "<hr><div style='color: green'> ".(++$index).": processing the file Settings/$file</div>";
        $subIndex = 0;
        if(count($diff) > 0) {
            foreach ($diff as $key=>$value) {
                echo "<div style='margin-left: 10px'>".(++$subIndex).": the item <b style='color: blue'>$key = $value</b> is not exist in fa_ir</div>";
            }
        }

        if(count($js_diff) > 0) {
            foreach ($js_diff as $key=>$value) {
                echo "<div style='margin-left: 10px'>".(++$subIndex).": the item <b style='color: blue'>$key = $value</b> is not exist in fa_ir</div>";
            }
        }
    }
}


