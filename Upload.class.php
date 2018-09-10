<?php 

	class Upload{

		private $upload_dir = 'upload/';//上传目录
		private $max_size = 1024*25;//上传文件大小限制25kb
		private $prefix = 'img_';//文件前缀
		private $allows_type = array('image/jpg','image/jpeg','image/png','image/gif');//文件类型

		public function __set($pro,$val){
			if (property_exists($this,$pro)) {
				$this->$pro = $val;
			}
		}
		public function __get($pro){
			if (property_exists($this,$pro)) {
				return $this->$pro;
			}
		}

		//上传方法
		public function getUpload($file){
			// 1.判断文件大小
			if ($file['size'] > $this->max_size) {
				die('上传文件太大，请重新上传！');
			}
			// 2.判断文件类型
			if (!in_array($file['type'],$this->allows_type)) {
				die('文件格式不支持！');
			}
			//获取文件真实类型再判断
			$finfo = new finfo(FILEINFO_MIME_TYPE);
			$finfo_type = $finfo->file($file['tmp_name']);
			if (!in_array($finfo_type,$this->allows_type)) {
				die('文件格式不支持！');
			}
			// 3.创建日期目录
			$sub_dir = date('md').'/';
			$dir_path = $this->upload_dir.$sub_dir;
			if (!is_dir($dir_path)) {
				mkdir($dir_path,0777,true);
			}
			// 4.文件重命名
			$file_name = uniqid($this->prefix,true);
			//拼接文件后缀
			$file_name .= strrchr($file['name'],'.');

			// 5.上传
			//上传到哪里
			$file_path = $dir_path.$file_name;
			if (move_uploaded_file($file['tmp_name'],$file_path)) {
				return $sub_dir.$file_name;
			}else{
				return false;
			}
		}


	}

	// 使用方法
	$file = isset($_FILES['file'])?$_FILES['file']:'';
	$p = new Upload;
	$p->max_size = 1024*75;
	if ($file != '') {
		$p->getUpload($file);
	}
?>