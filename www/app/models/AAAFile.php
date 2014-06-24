<?php

class File extends DB\SQL\Mapper{

    public function __construct(DB\SQL $db){
        parent::__construct($db, 'files');
    }

    public function getList(){
        $limit = array( 'order' => 'id DESC',
                        'limit' => '100');
        $s =  $this->find(null, $limit);
        foreach ($s as $key) {
            $key->title = $this->getFileTitle($key->title);
            $key->path = $this->getFilePath($key->id, $key->title);
        }
        return $s;
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

    public function getFileTitle($title){
        return substr(strstr($title, '_'),1);
    }

    public function getFilePath($id, $title){
        return intval($id/500).'/'. $title;
    }

}