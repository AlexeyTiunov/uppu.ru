<?php

class FileController extends Controller{
    public function index(){
        $this->f3->set('page_head', 'Upload File');
        $this->f3->set('view', 'file/main.htm');
    }

    public function showList(){
        $file = new File($this->db);
        $this->f3->set('list', $file->getList());
        $this->f3->set('page_head', '100 last files');
        $this->f3->set('view', 'file/list.htm');
    }

    public function showFile(){
        $file = new File($this->db);
        $result = $file->getById($this->f3->get('PARAMS.id'));
        $path = '/' . $this->getPath($file);
        //есть ли файл с таким id 
        if(!$result){
            $this->f3->error('404');
        }
        //проверка, является ли файл картинкой
        if($this->isImage($file)){
            $this->f3->set('isImage', true);  
            if(!file_exists($file->getThumbnailPath($this->f3->get("PREVIEW")))){
                $this->makePreviewImage($file);
            }
        } else {
            $this->f3->set('isImage', false);
        }
        //$this->f3->set('fileTitle', $file->getFileTitle());
        $this->f3->set('fileinfo', $result);
        $this->f3->set('path', $path);
        $this->f3->set('view', 'file/file.htm');
    }

    public function uploadFile(){
        $file = new File($this->db);
        //проверка на ошибки при загрузке файла
        if (!($this->f3->get("FILES['file']['error']") == 0)) { 
            $err =  new UploadError($this->f3->get("FILES['file']['error']")); 
            $errMessage = $err->getMessage();
            $this->f3->set('err', $errMessage);
            $this->f3->set('page_head', 'Upload File');
            $this->f3->set('view', 'file/main.htm');
        } else {
            //переименовываем файл до копирования в папку
            $file->add($this->f3->get("FILES['file']"));
            $name = $this->rename($file);
            $this->f3->set("FILES['file']['name']", $name);
            //Выбрать или создать папку для сохранения файла
            $this->f3->set('UPLOADS', $this->chooseFolder($file));
            //Сохранить файл, без функций, не перезаписывать, не менять на латиницу 
            \Web::instance()->receive(null, false, false);
            $this->f3->reroute('/file/'.$file['id']);
        } 
    }

    //создать превью изображение
    public function makePreviewImage($result){
        $img = new Image($this->getPath($result));
        $width = $img->width();
        $height = $img->height();
        if($width > 200){
            $width = 200;
        }
        if ($height > 200){
            $height = 200;
        }
        $img->resize($width, $height);
        $preview_path = $result->getThumbnailPath($this->f3->get("PREVIEW"));
        $this->f3->write($preview_path, $img->dump('png'));
    }

    //переименовать загружаемый файл для уникальности 
    public function rename($file){
        $name = $file->id .'_'. $file->title;
        $file->title = $name;
        $file->save();
        return $name;
    }

    //получить путь до загруженного файла
    public function getPath($file){
        $folderName = $file->getFolderName();
        return ($this->f3->get('UPLOADS') . $folderName . ($file->title));
    }

    //выбрать или создать папку для загружаемого файла
    public function chooseFolder($file){
        $folderName = $file->getFolderName();
        $folderPath = $this->f3->get('UPLOADS') . $folderName;
        if(!file_exists($folderPath)){
           mkdir($folderPath); 
        }
        return $folderPath;  
    }

    //является ли загружаемый файл картинкой
    public function isImage($file){
        $path = '/' . $this->getPath($file);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $this->f3->get('ROOT') . $path);
        if(preg_match('/image\/(jpeg|png|gif)/', $type)){
            return true;
        } else {
            return false;
        }
        
    }
}