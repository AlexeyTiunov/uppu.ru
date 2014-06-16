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
        } else {
            if(!file_exists($this->f3->get("PREVIEW").md5($result->title).".png")){
                $this->makePreviewImage($result);
            }
        }
        $this->f3->set('path', $path);
        $this->f3->set('view', 'file/file.htm');
    }

    public function uploadFile(){
        $file = new File($this->db);
        //проверка на ошибки при загрузке файла
        if (!($this->f3->get("FILES['file']['error']") == 0)) { 
            throw new UploadException($this->f3->get("FILES['file']['error']")); 
        } 
        //проверить картинка или нет для отображения тэга
        if(preg_match('/jpeg|jpg|png/',$this->f3->get("FILES['file']['type']"))){
            $file->set('image', true);
        } else {
            $file->set('image', false);
        }
        //переименовываем файл до копирования в папку
        $file->add($this->f3->get("FILES['file']"));
        $name = $this->rename($file);
        $this->f3->set("FILES['file']['name']", $name);
        //без функций, не перезаписывать, не менять на латиницу 
        \Web::instance()->receive(null, false, false);
        $this->f3->reroute('/file/'.$file['id']);
    }

    public function makePreviewImage($result){
        if($result->image){
            $img = new Image($this->f3->get('UPLOADS') . $result->title);
            $width = $img->width();
            $height = $img->height();
            if($width > 200){
                $width = 200;
            }
            if ($height > 200){
                $height = 200;
            }
            $img->resize($width, $height);
            $file_name = md5($result->title);
            $preview_path = $this->f3->get('PREVIEW').$file_name.'.png';
            $this->f3->write($preview_path, $img->dump('png'));
        }
    }

    public function rename($file){
        $name = $file->id .'_'. $file->title;
        $file->title = $name;
        $file->save();
        return $name;
    }

    public function getPath($file){
        return ($this->f3->get('UPLOADS') . ($file->title));
    }
}