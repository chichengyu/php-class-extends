<?php 
	interface I_DAO{
		//查询一条数据
		public function fetchOne($sql);
		//查询所有数据
		public function fetchAll($sql);
		//查询一个字段的数据
		public function fetchColumn($sql);
		//执行增删改的操作
		public function exec($sql);
		//引号转义并包裹的方法
		public function quote($sql);
		//查询刚刚添加的数据的主键
		public function lastInsertId();
	}
 ?>