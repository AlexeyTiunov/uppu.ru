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
                $message = "Error: File is too large."; 
                break;   
            case UPLOAD_ERR_NO_FILE: 
                $message = "Error: Select a file";
                break;
            default: 
                $message = "Error: Try again later."; 
                break; 
        } 
        return $message; 
    } 

    public function getMessage(){
        return $this->message;
    }
} 