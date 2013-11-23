<?php
class Hook{

    private $active_plugins = null;

    private $plugins = array();

    // hooks data
    private $hooks = array();

    // errors data
    private $errors = array();

    // Hold an instance of the class
    private static $instance;
    
    private function __construct() {}

    // The singleton method
    public static function singleton(){
        if (!isset(self::$instance)){
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    /**
     *
     * Enter description here ...
     * @param $tag
     * @param $args
     */
    public function execute($tag, $args = array()){
        if (isset ( self::$instance->hooks [$tag] )) {
            $hooks = self::$instance->hooks [$tag];
            if (is_array($hooks)){
                ksort($hooks);
                foreach ($hooks as $hook){
                    foreach ( $hook as $h) {
                        call_user_func($h,$args);
                    }
                }
            }else{
                self::$instance->errors[] =  "There is no such place ($tag) for hooks.";
            }
        } else {
            self::$instance->errors[] =  "There is no such place ($tag) for hooks.";
        }
    }
    /**
     *
     * Enter description here ...
     * @param $tag
     * @param $args
     */
    public function filter($tag, $args = ''){
        $result = $args;
        if (isset ( self::$instance->hooks [$tag] )) {
            $hooks = self::$instance->hooks [$tag];
            if (is_array($hooks)){
                ksort($hooks);
                foreach ($hooks as $hook){
                    foreach ( $hook as $h) {
                        $args  = $result;
                        $result = call_user_func ( $h, $args );
                    }
                }
            }else{
                self::$instance->errors[] =  "There is no such place ($tag) for hooks.";
            }
            return $result;
        } else {
            self::$instance->errors[] =  "There is no such place ($tag) for hooks.";
        }
    }
    /**
     *
     * Enter description here ...
     * @param  $tag
     * @param  $function
     * @param  $priority
     */
    public function add_function($tag, $function, $priority = 10){
        if (! isset ( self::$instance->hooks [$tag] )) {
            self::$instance->errors[] =  "There is no such place ($tag) for hooks.";
        } else {
            self::$instance->hooks [$tag] [$priority] [] = $function;
        }
    }
    public function isExisted($tag){
        return (isset(self::$instance->hooks[$tag]) && is_array(self::$instance->hooks[$tag]))?true:false;
    }

    /**
     *
     * Enter description here ...
     * @param  $tag
     */
    public function set($tag){
        if (is_array($tag)){
            foreach ($tag as $t){
                self::$instance->hooks[$t] = '';
            }
        }else{
            self::$instance->hooks[$tag] = '';
        }
    }
    /**
     *
     * Enter description here ...
     * @param $tag
     */
    public function remove($tag){
        if (is_array($tag)){
            foreach ($tag as $t){
                if (isset(self::$instance->hooks[$t])){
                    unset(self::$instance->hooks[$t]);
                }
            }
        }else{
            if (isset(self::$instance->hooks[$tag])){
                unset(self::$instance->hooks[$tag]);
            }
        }
    }

    public function set_active_plugins($active_plugins = null){
        self::$instance->active_plugins = $active_plugins;
    }

    public function load_plugins($from_folder = PLUGINS_FOLDER){
        if (is_dir($from_folder)){
            if ($handle = @opendir ( $from_folder )) {
                while ( $file = readdir($handle)){
                    if (is_file($from_folder.'/'.$file )) {
                        if ((self::$instance->active_plugins == null || in_array( $file, self::$instance->active_plugins)) &&
                        strpos($from_folder.'/'.$file,'.plugin.php')){
                            require_once $from_folder.'/'.$file;
                            self::$instance->plugins[$file]['file'] = $file;
                        }
                    } else if ((is_dir ( $from_folder.'/'.$file )) && ($file != '.') && ($file != '..') && ($file != '.svn')) {
                        self::$instance->load_plugins ( $from_folder.'/'.$file);
                    }
                }
                closedir ( $handle );
            }
        }
    }

    public function errors(){
        return self::$instance->errors;
    }

    // Prevent users to clone the instance
    public function __clone(){
        self::$instance->errors[] =  'Clone is not allowed.'.E_USER_ERROR;
    }
}