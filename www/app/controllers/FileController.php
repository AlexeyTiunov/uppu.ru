<?php

class FileController extends Controller{
    public function index(){
        $this->f3->set('view', 'file/main.htm');
    }

    public function showList(){
        $file = new File($this->db);
        $this->f3->set('count', $file->count());
        $this->f3->set('list', $file->getList());
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
        //если картинка проверить/сделать превью
        if($result->image != 0){
            if(!file_exists($file->getThumbnailPath($this->f3->get("PREVIEW")))){
                $this->makePreviewImage($file);
            }
        }
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
            if($this->f3->get('PATH') != "/"){
                $this->showList();
            } else {
                $this->index();
            }
            
        } else {
            //переименовываем файл до копирования в папку
            $file->add($this->f3->get("FILES['file']"));
            //проверка на картинку
            if($this->isImage()){
                $file->image = true;
            }
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
        $folderName = $file->getFilePath();
        return ($this->f3->get('UPLOADS') . $folderName);
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
    public function isImage(){
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $this->f3->get("FILES[file][tmp_name]"));
        if(preg_match('/image\/(jpeg|png|gif)/', $type)){
            return true;
        } else {
            return false;
        }
        
    }
}