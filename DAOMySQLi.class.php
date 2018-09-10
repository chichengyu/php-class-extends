<?php 
#############################################
############ DAOMySQLi.class.php ############
#############################################
############ author：小池 ###################
#############################################
	
##功能：完成对数据库的操作,单利模式

// 开发类
// 1.定类名
// 2.定成员属性
// 3.定成员方法

/*
	@param $_host    ---> 主机名
	@param $_user    ---> 用户名
	@param $_pwd     ---> 密码
	@param $_dbname  ---> 数据库名
	@param $_port    ---> 端口号
	@param $_charset ---> 字符集
*/
	class DAOMySQLi{
		// 将成员属性以_开头是一种命名风格,老外比较喜欢
		private $_host;
		private $_user;
		private $_pwd;
		private $_dbname;
		private $_port;
		private $_charset;

		private $_mysqli;
		private static $_instance;

		// 初始化
		private function __construct(array $option){
			$this->_host = isset($option['host'])?$option['host']:'';
			$this->_user = isset($option['user'])?$option['user']:'';
			$this->_pwd = isset($option['pwd'])?$option['pwd']:'';
			$this->_dbname = isset($option['dbname'])?$option['dbname']:'';
			$this->_port = isset($option['port'])?$option['port']:'';
			$this->_charset = isset($option['charset'])?$option['charset']:'';
			if ($this->_host == ''||$this->_user == ''||$this->_pwd == ''||$this->_dbname == ''||$this->_port == ''||$this->_charset == '') {
				die('传入参数有误！');
			}

			$this->_mysqli = new mysqli($this->_host,$this->_user,$this->_pwd,$this->_dbname,$this->_port);
			if ($this->_mysqli->connect_errno) {
				die('连接数据库失败！错误信息是'.$this->_mysqli->connect_errno);
			}
			//设置字符集
			$this->_mysqli->set_charset($this->_charset);

		}
		// 防止克隆
		private function __clone(){}

		// 静态方法,通过这个静态方法来创建对象实例
		public static function getSingleton(array $option){
			// 判断是否已有对象实例
			if (!self::$_instance instanceof self) {
				// 创建对象
				self::$_instance = new DAOMySQLi($option);
			}
			return self::$_instance;
		}
		// DQL 操作 select查询语句
		public function fetchAll($sql){
			// 存放结果数据
			$arr = array();
			//返回结果集
			if ($res = $this->_mysqli->query($sql)) {
				//解析结果集
				while ($row = $res->fetch_assoc()) {
					$arr[] = $row;
				}
				// 释放 $res
				$res->free();
				return $arr;
			}else{
				echo "<br>执行失败！sql语句是：".$sql;
				echo "<br>失败！原因是：".$this->_mysqli->error;
				exit;
			}
		}
		// DQL 操作 select查询一条语句
		public function fetchOne($sql){
			if ($res = $this->_mysqli->query($sql)) {
				// 只有一条数据,所以不用wile循环,if判断就可以
				if ($row = $res->fetch_assoc()) {
					return $row;
				}else{
					echo "<br>返回数据失败！错误信息是：".$this->_mysqli->error;
					exit;					
				}
			}else{
				echo "<br>执行失败！sql语句是：".$sql;
				echo "执行失败的原因是：".$this->_mysqli->error;
				exit;
			}
		}
		// DML 操作 增删改查
		public function query($sql){
			if ($this->_mysqli->query($sql)) {
				return true;
			}else{
				echo "<br>执行失败！sql语句是".$sql;
				echo "<br>失败！原因是".$this->_mysqli->error;
				exit;
			}
		}
		// 返回刚刚添加数据的【自动增长】生成的 id
		public function queryKey(){
			return $this->_mysqli->insert_id;
		}
	}
 ?>