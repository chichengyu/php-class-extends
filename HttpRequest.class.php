<?php 
/*
	使用curl扩展出的httprequest或post请求
*/
	class HttpRequest{
		// url 请求的服务器地址
		private $url = '';
		//is_return 是否直接返回结果,而不是直接显示
		private $is_return = 1;

		public function __set($pro,$val){
			if (property_exists($this,$pro)) {
				return $this->$pro = $val;
			}
		}

		//发出http请求的方法
		// 参数：提交的数据,默认是空的
		public function send($data=array()){
			// 1.初始化
			$curl = curl_init();

			// 2.不管是get、post,跳过http证书的验证
			curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);

			// 3.设置选项：这种请求的服务器地址
			curl_setopt($curl,CURLOPT_URL,$this->url);

			// 4.判断是get读取还是post提交
			//如果传递数据了,说明是post向服务器提交数据,如果没有传递数据,认为从服务器get读取资源
			if (!empty($data)) {
				//post向服务器提交资源
				//开启Post提交数据
				curl_setopt($curl,CURLOPT_POST,true);
				//设置提交的数据
				curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
			}
			// 5.判断是否返回数据
			if ($this->is_return === 1) {
				//返回数据
				curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
				$res = curl_exec($curl);
				//关闭资源
				curl_close($curl);
				return $res;
			}else{
				//直接输出
				$res = curl_exec($curl);
				//关闭资源
				curl_close($curl);
			}
		}
	}


	$http = new HttpRequest();
	$http->url = 'http://www.baidu.com/index.php'; 
	$res = $http->send();
	echo "<pre>";
	var_dump($res);

 ?>