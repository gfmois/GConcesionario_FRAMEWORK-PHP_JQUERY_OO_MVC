<?php
    require_once 'paths.php';
    require_once 'utils/StrToArray.inc.php';

    spl_autoload_extensions('.php,.inc.php,.class.php,.class.singleton.php');
    spl_autoload_register('loadClasses');

    function loadClasses($module, $limit = "") {
        if (str_contains($module, "Controller")) {
            $limit = "Controller";
            $breakClass = strToArray($module, $limit);
        } else $breakClass = [0 => $module];
        $modelName = "";

        if (isset($breakClass[1])) {
            $modelName = strtolower($breakClass[1]);
        }

        if (file_exists(SITE_ROOT . 'module/' . strtolower($breakClass[0]) . '/model/'. $modelName . '/' . $module . '.class.singleton.php')) {
            set_include_path('module/' . strtolower($breakClass[0]) . '/model/' . $modelName.'/');
            spl_autoload($module);
        } else if (file_exists(SITE_ROOT . 'model/' . $module . '.class.singleton.php')){
            set_include_path(SITE_ROOT . 'model/');
            spl_autoload($module);
        } else if (file_exists(SITE_ROOT . 'model/' . $module . '.class.php')){
            set_include_path(SITE_ROOT . 'model/');
            spl_autoload($module);
        } else if (file_exists(SITE_ROOT . 'utils/' . $module . '.inc.php')) {
            set_include_path(SITE_ROOT . 'utils/');
            spl_autoload($module);
        }
    }
?>