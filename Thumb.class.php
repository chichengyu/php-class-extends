<?php 
	class Thumb{
		private $file;//图像文件
		private $thumb_path = 'thumb/';//压缩后的文件保存路径
		private $mime;//图像文件的类型
		//创建原图资源的函数
		private $create_func = array(
			'image/png'  => 'imagecreatefrompng',
			'image/jpg'  => 'imagecreatefromjpeg',
			'image/jpeg' => 'imagecreatefromjpeg',
			'image/gif'  => 'imagecreatefromgif'
		);
		//保存图像资源的函数
		private $output_func = array(
			'image/png'  => 'imagepng',
			'image/jpg'  => 'imagejpeg',
			'image/jpeg' => 'imagejpeg',
			'image/gif'  => 'imagegif'
		);

		public function __construct($file){
			//判断文件是否存在
			if (!file_exists($file)) {
				die('文件无效！请选择正确的文件！');
			}
			$this->file = $file;
			//getimagesize获取文件信息,返回一个数组
			$this->mime = getimagesize($file)['mime'];
		}

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
		//图像压缩
		public function makeThumb($area_w,$area_h){
			// 创建原图资源
			$create_func = $this->create_func[$this->mime];
			$src_image = $create_func($this->file);
			// 原图起点坐标
			$src_x = 0;
			$src_y = 0;
			// 原图宽高
			$src_w = imagesx($src_image);
			$src_h = imagesy($src_image);

			//画布宽高计算(即等比例放大缩小后的图片宽高)
			if ($src_w >= $src_h) {
				$img_w = $area_w;
				$img_h = $src_h * $area_w / $src_w;
			}else{
				$img_w = $src_w * $area_h / $src_h;
				$img_h = $area_h;
			}
			// 画布起点坐标
			$dst_x = 0;
			$dst_y = 0;
			// 目的画布宽高
			$dst_w = $img_w;
			$dst_h = $img_h;
			// 创建画布
			$dst_image = imagecreatetruecolor($dst_w,$dst_h);
			$bgcolor = imagecolorallocate($dst_image,255,255,255);
			imagefill($dst_image,0,0,$bgcolor);
			imagecolortransparent($dst_image,$bgcolor);

			imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

			//创建日期目录
			$sub_path = date('md').'/';
			$path = $this->thumb_path.$sub_path;
			if (!is_dir($path)) {
				mkdir($path,0777,true);
			}

			//图像文件重命名
			$filename = basename($this->file);
			$path .= $filename;

			// 1.使用imagepng的第二个参数把压缩文件保存
			// 通常把压缩文件保存到thumb子目录中,按日期格式的子目录保存
			header('Content-Type:'.$this->mime.'');
			$output_func = $this->output_func[$this->mime];
			$output_func($dst_image,$path);
			// 将文件的相对路径返回出去,便于使用
			return $sub_path.$filename;
		}
	}
	$p= new Thumb('01.png');
	$p->makeThumb(200,200);

 ?>