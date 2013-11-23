<?php

class Validation {

    private $check;
    private $regex;
    // Hold an instance of the class
    private static $instance;

    // The singleton method
    public static function singleton() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    public function notEmpty($check) {
        $this->reset();
        $this->check = $check;
        if (empty($this->check) && $this->check != '0') {
            return false;
        }
        $this->regex = '/[^\s]+/mu';
        return $this->check();
    }

    public function alpha($check) {
        $this->reset();
        $this->check = $check;

        if (empty($this->check) && $this->check != '0') {
            return false;
        }
        $this->regex = '/^\pL+$/u';
        return $this->check();
    }

    public function alphaSpace($check) {
        $this->reset();
        $this->check = $check;

        if (empty($this->check) && $this->check != '0') {
            return false;
        }
        $this->regex = '/^[A-Za-z ]*$/';
        return $this->check();
    }

    public function alphaNumeric($check) {
        $this->reset();
        $this->check = $check;

        if (empty($this->check) && $this->check != '0') {
            return false;
        }
        //$this->regex = '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]+$/mu';
        $this->regex = '/^[A-Za-z0-9-_]*$/';
        return $this->check();
    }

    public function alphaNumericSpace($check) {
        $this->reset();
        $this->check = $check;

        if (empty($this->check) && $this->check != '0') {
            return false;
        }
        $this->regex = '/^[A-Za-z0-9 ]*$/';
        return $this->check();
    }

    public function email($check, $deep = false, $regex = null) {
        $this->reset();
        $this->check = $check;
        $this->regex = $regex;
        $this->deep = $deep;

        if (is_null($this->regex)) {
            $this->regex = "/^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[a-z]{2,4}|museum|travel)$/i";
        }
        $return = $this->check();

        if ($this->deep === false || $this->deep === null) {
            return $return;
        }

        if ($return === true && preg_match('/@([a-z0-9][a-z0-9\\.\\-]{0,63}\\.([a-z]*))/', $this->check, $regs)) {
            $host = gethostbynamel($regs[1]);
            if (is_array($host)) {
                return true;
            }
        }
        return false;
    }

    public function ip($check) {
        $bytes = explode('.', $check);
        if (count($bytes) == 4) {
            foreach ($bytes as $byte) {
                if (!(is_numeric($byte) && $byte >= 0 && $byte <= 255)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function numeric($check) {
        return is_numeric($check);
    }

    public function url($check) {
        $this->reset();
        $this->check = $check;
        $this->regex = '/^(?:(?:https?|ftps?|file|news|gopher):\\/\\/)?(?:(?:(?:25[0-5]|2[0-4]\d|(?:(?:1\d)?|[1-9]?)\d)\.){3}(?:25[0-5]|2[0-4]\d|(?:(?:1\d)?|[1-9]?)\d)'
                . '|(?:[0-9a-z]{1}[0-9a-z\\-]*\\.)*(?:[0-9a-z]{1}[0-9a-z\\-]{0,62})\\.(?:[a-z]{2,6}|[a-z]{2}\\.[a-z]{2,6})'
                . '(?::[0-9]{1,4})?)(?:\\/?|\\/[\\w\\-\\.,\'@?^=%&:;\/~\\+#]*[\\w\\-\\@?^=%&\/~\\+#])$/i';
        return $this->check();
    }

    function date($date) {
        if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
            if (checkdate($parts[2], $parts[3], $parts[1]))
                return true;
            else
                return false;
        }
        else
            return false;
    }
    
    function datetime($datetime){
    	if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $datetime, $matches)){
    		if (checkdate($matches[2], $matches[3], $matches[1])){
    			return true;
    		}
    	}
    	return false;
    }
    

    private function check() {
        $this->check = (empty($this->check))?'':$this->check;
        $this->check = (is_array($this->check))?','.implode(',', $this->check).',' :$this->check;
        if (preg_match($this->regex, $this->check)) {
            return true;
        } else {
            return false;
        }
    }

    private function reset() {
        $this->check = null;
        $this->regex = null;
    }

    // Prevent users to clone the instance
    public function __clone() {
        $this->errors[] = 'Clone is not allowed.' . E_USER_ERROR;
    }

}