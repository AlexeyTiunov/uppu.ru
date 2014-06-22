<?php

class File extends DB\SQL\Mapper{

    public function __construct(DB\SQL $db){
        parent::__construct($db, 'files');
    }

    public function getList(){
        $limit = array( 'order' => 'id DESC',
                        'limit' => '100');
        return $this->find(null, $limit);
    }

    public function getById($id){
        $result = $this->load(array('id=?', $id));
        //есть ли запись с таким id
        if(!$result){
            return false;
        }
        $this->copyTo('FILEINFO');
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

}