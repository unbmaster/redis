<?php
/*
 * This file is part of the https://github.com/unbmaster
 * For demonstration purposes, use it at your own risk.
 * (c) UnBMaster <unbmaster@outlook.com>
 * License GNU General Public License (GPL)
 */
namespace Core;

/**
 * Config class
 *
 * Manipula variárveis em arquivos de configuração src/config
 * @author UnBMaster <unbmaster@outlook.com>
 * @version 0.1.0
 */
class Config
{
    public function __invoke($path)
    {
        return self::run($path);
    }

    public static function get($path)
    {
        return self::run($path);
    }

    public function run($path) {
        $node = explode('.', $path);
        $result = null;
        $filename = filter_var ( $node[0], FILTER_SANITIZE_STRING);
        $filepath = str_replace('core', '',__DIR__)  . 'config/'. $filename . '.php';
        if (file_exists($filepath)) {
            $data = require $filepath;
            $info = '';
            for ($i = 1; $i < count($node); $i++) {
                $info .= "['$node[$i]']";
            }
            $cmd = '$data' . $info . ';';
            eval("\$result=$cmd;");
        } else {
            return "File {$node[0]} not found in config folder.";
        }
        return $result;
    }
}