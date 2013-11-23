<?php
class FileUpload {
    
    public $fileName;
    public $tmpFileName;
    public $newFileName;
    
    public $uploadDir;
    public $extensions;
    public $httpError;
    
    private $message = array();

    public function __construct() {
        
    }

    public function getMessage() {
        return $this->message;
    }

    private function createFileName() {
        return strtotime("now").'-'.$this->fileName;
    }

    function upload() {
        $CI = & get_instance();
        $new_name = $this->createFileName();
        if (is_uploaded_file($this->tmpFileName)) {
            if ($this->validateExtension()) {
                $this->newFileName = $new_name;
                if ($this->moveUpload($this->tmpFileName, $this->newFileName)) {
                    return true;
                }
            } else {
                $this->message[] = sprintf($CI->lang->line('E_VAL_FILE_EXTENSIONS'), implode(" ", $this->extensions));
                return false;
            }
        } else {
            if ($this->httpError != UPLOAD_ERR_NO_FILE){
                $this->message[] = $this->errorText($this->httpError);
            }
            return false;
        }
    }

    private function getExtension($from_file) {
        $ext = strtolower(strrchr($from_file, "."));
        return $ext;
    }

    private function validateExtension() {
        $extension = $this->getExtension($this->fileName);
        $ext_array = $this->extensions;
        if (in_array($extension, $ext_array)) {
            return true;
        } else {
            return false;
        }
    }

    private function moveUpload($tmp_file, $new_file) {
        $CI = & get_instance();
        umask(0);
        if ($this->existingFile($new_file)) {
            $newfile = $this->uploadDir . $new_file;
            if ($this->checkDir($this->uploadDir)) {
                if (@move_uploaded_file($tmp_file, $newfile)) {
                    return true;
                } else {
                    $this->message[] =  sprintf($CI->lang->line('E_VAL_FOLDER_NOT_ALLOWED_WRITE'), $this->uploadDir);
                    return false;
                }
            } else {
                $this->message[] = sprintf($CI->lang->line('E_VAL_FOLDER_NOT_EXIST'), $this->uploadDir);
                return false;
            }
        } else {
            $this->message[] = sprintf($CI->lang->line('E_VAL_FILE_EXISTED'), $this->fileName);
            return false;
        }
    }

    private function checkDir($directory) {
        if (!is_dir($directory)) {
            umask(0);
            if (@mkdir($directory, 0777)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    private function existingFile($fileName) {
        if (file_exists($this->uploadDir . $fileName)) {
            return false;
        } else {
            return true;
        }
    }

    public function fileInfo($name) {
        $str = "File name: " . basename($name) . "\n";
        $str .= "File size: " . filesize($name) . " bytes\n";
        if (function_exists("mime_content_type")) {
            $str .= "Mime type: " . mime_content_type($name) . "\n";
        }
        if ($img_dim = getimagesize($name)) {
            $str .= "Image dimensions: x = " . $img_dim[0] . "px, y = " . $img_dim[1] . "px\n";
        }
        return $str;
    }

    public function delFile($fileName) {
        if (is_file($this->uploadDir.$fileName)){
            $delete = @unlink($this->uploadDir.$fileName);
            clearstatcache();
            if (@is_file($this->uploadDir.$fileName)) {
                $filesys = eregi_replace("/", "\\", $this->uploadDir.$fileName);
                $delete = @system("del $filesys");
                clearstatcache();
                if (@is_file($this->uploadDir.$fileName)) {
                    $delete = @chmod($this->uploadDir.$fileName, 0775);
                    $delete = @unlink($this->uploadDir.$fileName);
                    $delete = @system("del $filesys");
                }
            }
        }
    }

    private function errorText($err_num) {
        $CI = & get_instance();
        // start http errors
        $error[1] = $CI->lang->line('HTTP_ERROR_1');
        $error[2] = $CI->lang->line('HTTP_ERROR_2');
        $error[3] = $CI->lang->line('HTTP_ERROR_3');
        $error[4] = $CI->lang->line('HTTP_ERROR_4');
        // end  http errors
        
        return $error[$err_num];
    }

}