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
		$this->copyTo('POST');
		return $this->title;
	}

	public function add($file){
		$this->set('title', $file['name']);
		$this->set('size', $file['size']);
		$this->set('timestamp', date("Y-m-d H:i:s"));
		$this->save();
	}

	public function edit($file, $name){
		$this->load(array('id=?', $file->id));
		$this->set('title', $name);
		$this->update();
	}
}