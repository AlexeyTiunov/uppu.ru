<?php 

class UploadError{ 
    private $message;

    public function __construct($code) { 
        $this->message =  $this->codeToMessage($code); 
    } 

    private function codeToMessage($code) 
    { 
        switch ($code) { 
            case UPLOAD_ERR_INI_SIZE: 
            case UPLOAD_ERR_FORM_SIZE: 
                $message = "Ошибка: размер файла превышает допустимую величину."; 
                break;   
            case UPLOAD_ERR_NO_FILE: 
                $message = "Ошибка: выберите файл.";
                break;
            default: 
                $message = "Ошибка: попробуйте повторить позже."; 
                break; 
        } 
        return $message; 
    } 

    public function getMessage(){
        return $this->message;
    }
} 