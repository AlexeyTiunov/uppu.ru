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
		//есть ли файл с таким id
		if(!$result){
			$this->f3->reroute('/');
			//$this->f3->error('404');
		}
		$path = '/' . $this->getPath($file);
		//проверить картинка или нет для отображения тэга
		if(preg_match('/jpeg|jpg|png/', \Web::instance()->mime($path))){
			$this->f3->set('img', true);
		}
		$this->f3->set('path', $path);
		$this->f3->set('page_head', 'Your Uploaded File');
		$this->f3->set('view', 'file/file.htm');
	}

	public function uploadFile(){
		$file = new File($this->db);
		if($this->f3->get("FILES['file']['size']") == 0){
			$this->f3->reroute('/');
		}
		$file->add($this->f3->get("FILES['file']"));
		//без функций, не перезаписывать, не менять на латиницу
		\Web::instance()->receive(null, false, false);
		$this->rename($file);
		$this->f3->reroute('/file/'.$file['id']);
	}

	public function drawImage(){
		$file = new File($this->db);
		$title = $file->getById($this->f3->get('PARAMS.id'));
		$img = new Image($this->f3->get('UPLOADS') . $title);
		$width = $img->width();
		$height = $img->height();
		if($width > 200){
			$width = 200;
		}
		if ($height > 200){
			$height = 200;
		}
		$img->resize($width, $height);
		$img->render();
	}

	public function rename($file){
		$name = $file->id .'_'. $file->title;
		rename($this->getPath($file), $this->f3->get('UPLOADS') . $name);
		$file->edit($file, $name);
	}

	public function getPath($file){
		return ($this->f3->get('UPLOADS') . ($file->title));
	}
}