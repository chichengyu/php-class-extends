<?php 
	
	#封装验证码类
	class Captcha{
		//成员属性
		private $width = 100;// 画布宽度
		private $height = 30;// 画布高度
		private $number = 4;// 验证码的字符个数
		private $font_file = 'font.TTF';//验证码的字体文件
		private $fontsize = 22;//字体大小

		//修改、增加的方法
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
		//生成验证码
		public function makeImage(){
			// 1.创建画布
			$image = imagecreatetruecolor($this->width,$this->height);
			// 画布的随机背景色
			// mt_rand生成更好的随机数
			$bgcolor = imagecolorallocate($image,mt_rand(100,255),mt_rand(100,255),mt_rand(100,255));
			// 2.填充画布颜色
			imagefill($image, 0, 0,$bgcolor);
			// 3.开始绘制验证码
			$code = $this->makeCode();
			//将生成的字符用session存起来,便于和输入的进行验证
			session_start();
			$_SESSION['code'] = $code;
			// 循环取出字符串进行绘制
			//计算字符串的长度 strlen
			for ($i=0; $i < strlen($code); $i++) { 
				$x = ($this->width/$this->number)*$i+5;
				imagettftext($image,$this->fontsize,rand(-30,30),$x,20,mt_rand(0,100),$this->font_file,$code[$i]);
			}

			//绘制模糊文字的像素点
			// imagesetpixel 绘制干扰像素点
			for ($i=0; $i < 100; $i++) { 
				imagesetpixel($image,mt_rand(0,$this->width),mt_rand(0,$this->height), mt_rand(0,100));
			}
			//绘制10个干扰线条
			for ($i=0; $i < 10; $i++) { 
				$color = imagecolorallocate($image,mt_rand(100,150),mt_rand(100,150),mt_rand(100,150));
				$x1 = mt_rand(0,$this->width);
				$y1 = mt_rand(0,$this->height);
				$x2 = mt_rand(0,$this->width);
				$y2 = mt_rand(0,$this->height);
				imageline($image,$x1,$y1,$x2,$y2,$color);
			}

			// 4.输出图像到浏览器
			header('Content-Type:image/png');
			imagepng($image);
			// 5.关闭内存中的图像资源
			imagedestroy($image);
		}
		//由于生成随机字符的代码很多,便于维护,重开一个函数
		public function makeCode(){
			// range — 建立一个包含指定范围单元的数组 
			// 大写字母
			$upper = range('A','Z');
			// 小写字母
			$lower = range('a','z');
			// 数字
			$number = range('0','9');
			// 把大写字母、小写字母、数字 三个数组合成一个数组
			$code = array_merge($upper,$lower,$number);
			// shuffle — 将数组打乱
			shuffle($code);
			//$key = array_rand($code,4);
			$str = '';
			for ($i=0; $i < $this->number; $i++) { 
				$str .= $code[$i];
			}
			return $str;
		}
	}
	$p = new Captcha;
	$p->makeImage();
 ?>