<?php
spl_autoload_register(function ($sClassName) {
    $sClassFile = realpath(__DIR__.'/lib');
    if (is_dir($sClassFile)) {
        $arClass = explode('\\', $sClassName);

        foreach ($arClass as $sPath) {
            $sClassFile .= '/'.$sPath;
        }

        $sClassFile .= '.php';

        if (file_exists($sClassFile)) {
            require_once($sClassFile);
        }
    }
});
