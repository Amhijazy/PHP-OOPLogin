<?php
class Config {
    // get(path) is for easier access to $GLOBALS[config"] values
    // we can access any value by typong Config::get(path down the array)
    // example: Config::get(mysql/host); will return 127.0.0.1
    public static function get($path = null){ 
        if($path){
            $config = $GLOBALS['config'];
            $path = explode('/',$path); //['mysql','host']
            // explode function turns a string into an arra
            // separated by the first arg char

            foreach($path as $bit){
                if(isset($config[$bit])){
                    $config = $config[$bit]; // go deeper one level
                }
            }
        }
        return $config;
    }
}