<?php

class File extends DB\SQL\Mapper{

    public function __construct(DB\SQL $db){
        parent::__construct($db, 'files');
    }

    public function getList(){
        $limit = array( 'order' => 'id DESC',
                        'limit' => '100');
        return  $this->find(null, $limit);
    }

    public function getById($id){
        $result = $this->load(array('id=?', $id));
        //есть ли запись с таким id
        if(!$result){
            return false;
        }
        return $result;
    }

    public function add($file){
        $this->title = $file['name'];
        $this->size = $file['size'];
        $this->timestamp = date("Y-m-d H:i:s");
        $this->save();
    }

    public function getThumbnailPath($path){
        return $path . $this->title;
    }

    public function getFolderName(){
        return intval($this->id / 500) . '/';
    }

    public function getFileTitle(){
        return substr(strstr($this->title, '_'),1);
    }

    public function getFilePath(){
        return intval($this->id/500).'/'. $this->title;
    }

    public function getFileSize(){
        $size = $this->size;
        if($size > (1024 * 1024)){
            $size = round(($size / 1024 / 1024), 1) . " Mb";
        } else if($size > 1024){
            $size = round(($size / 1024), 1) . " Kb";
        } else {
            $size = $size . " b";
        }
        return $size;
    }
}