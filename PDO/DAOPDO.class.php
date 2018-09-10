<?php 

####################
##### 单利模式 #####
####################

	//引入规定好的接口方法
	require_once 'I_DAO.interface.php';

	class DAOPDO implements I_DAO{
		//私有属性,用来保存PDO实例对象
		private $pdo;

		//同来存放DAOPDO类本身得的单利对象
		private static $instance;


		//私有构造方法
		private function __construct(array $option){
			$_host = isset($option['host'])?$option['host']:'';
			$_user = isset($option['user'])?$option['user']:'';
			$_pwd = isset($option['pwd'])?$option['pwd']:'';
			$_dbname = isset($option['dbname'])?$option['dbname']:'';
			$_port = isset($option['port'])?$option['port']:'';
			$_charset = isset($option['charset'])?$option['charset']:'';

			if ($_host=='' || $_user=='' || $_pwd=='' || $_dbname=='' || $_port=='' || $_charset=='') {
				die('传入参数有误！');
			}
			//连接数据库
			$dsn = "mysql:host={$_host};dbname={$_dbname};port={$_port};charset={$_charset}";
			$user = $_user;
			$pass = $_pwd;
			try {
				$this->pdo = new PDO($dsn,$user,$pass);
			} catch (Exception $e) {
				echo "错误信息：".$e->getMessage();
			}
		}
		// 静态方法,通过这个静态方法来创建DAOPDO类本身对象实例
		public function getSingleton(array $option){
			//判断 $instance 是否是当前 DAOPDO 类本身的单利对象
			if (!self::$instance instanceof self) {
				self::$instance = new DAOPDO($option);
			}
			return self::$instance;
		}
		//防止克隆
		private function __clone(){}

		//查询一条数据
		public function fetchOne($sql){
			// 返回一个PDOStatement对象,失败返回false
			$pdo_statement = $this->pdo->query($sql);
			if (!$pdo_statement) {
				//返回false时
				$error = $this->pdo->errorInfo();
				echo "SQL语句有误,错误信息：".$error;
				return false;
			}
			//成功 返回PDOStatement对象
			$res = $pdo_statement->fetch(PDO::FETCH_ASSOC);
			//关闭游标指针
			$pdo_statement->closeCursor();
			return $res;
		}
		//查询所有数据
		public function fetchAll($sql){
			// 返回一个PDOStatement对象,失败返回false
			$pdo_statement = $this->pdo->query($sql);
			if (!$pdo_statement) {
				$error = $this->pdo->errorInfo();
				echo "SQL语句有误！错误信息：".$error;
				return false;
			}
			//成功 返回PDOStatement对象
			$res = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
			$pdo_statement->closeCursor();
			return $res;
		}
		//查询一个字段的数据
		public function fetchColumn($sql){
			// 返回一个PDOStatement对象,失败返回false
			$pdo_statement = $this->pdo->query($sql);
			if (!$pdo_statement) {
				$error = $this->pdo->errorInfo();
				echo "SQL语句有误！错误信息：".$error;
				return false;
			}
			//成功 返回PDOStatement对象
			//因为查询的时候sql语句是 select name from user where id=1
			//已经告诉数库查询的是name这个字段,所以fetchColumn时就不用再传递字段的索引
			$res = $pdo_statement->fetchColumn();
			$pdo_statement->closeCursor();
			return $res;
		}
		//执行增删改的操作  返回增删改受影响的记录数(行数)
		public function exec($sql){
			//exec()增删改返回的是受影响的行数
			$res = $this->$this->pdo->exec($sql);
			//避免的受影响0行的情况
			if ($res === false) {
				// PDO::errorInfo返回的是一个错误数组
				echo "SQL语句有误！错误信息：".$this->pdo->errorInfo();
				return false;
			}
			return $res;
		}
		//引号转义并包裹的方法  返回转义包裹之后的数据
		public function quote($data){
			//对象sql语句进行转义,可以防跳墙
			return $this->pdo->quote($data);
		}
		//查询刚刚添加的数据的主键
		public function lastInsertId(){
			return $this->pdo->lastInsertId();
		}
	}
 ?>