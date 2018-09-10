<?php
/*
 * 验证类：主要负责用户名、密码、邮箱、手机号码等规则的验证
 */
	namespace framework\tools;
	
	class Verify{
	    private $error = array();
	    
	    //获得错误信息
	    public function showError(){
	        $err_str = '';
	        foreach ($this->error as $v){
	            $err_str .= $v.'<br>';
	        }
	        return $err_str;
	    }
	    
	    //验证用户名是否符合规则
	    //参数1：$username待验证的用户名
	    //参数2：$min 最少多少位
	    //参数3：$max 最多多少位
	    public function checkUser($username,$min=6,$max=30){
	        //1. 用户名的验证规则：6-30位，字母、数字、下划线组合，字母开头
	        $min = $min-1;
	        $max = $max-1;
	        $reg = '/^[a-zA-Z]\w{'.$min.','.$max.'}$/';
	       
	        preg_match($reg, $username,$result);
	        
	        if($result){            
	            return true;
	        }else{
	            $this->error[] = '6-30位，字母、数字、下划线组合，字母开头';
	            return false;
	        }
	    }
	    //验证手机号码是否符合规则
	    //参数1：待验证的手机号码
	    public function checkPhone($phone){
	        //1. 定义手机号码的规则
	        $reg = '/^1[34578]\d{9}$/';     
	        preg_match($reg, $phone,$result);
	        
	        if($result){
	            return true;
	        }else{
	            $this->error[] = '请输入正确的手机号码';            
	            return false;
	        }
	    }
	    //验证密码是否符合规则
	    //参数1：$password待验证的密码
	    //参数2：$min 最少多少位
	    //参数3：$max 最多多少位
	    public function checkPass($password,$min=6,$max=20){
	        //1. 先定义一个纯字母、纯数字的规则
	        $reg1 = '/^[a-zA-Z]{'.$min.','.$max.'}$/'; //纯字母
	        $reg2 = '/^\d{'.$min.','.$max.'}$/';       //纯数字
	        $reg3 = '/^[a-zA-Z0-9]{'.$min.','.$max.'}$/';    //字母、数字的组合
	        $reg4 = '/^[a-zA-Z0-9~`!@#\$%\^&\*\(\)\-_\+=\{\}\[\]\|\\;:\'\"<>,\.\?\/]{'.$min.','.$max.'}$/';
	        
	        //获得用户输入的密码                
	        preg_match($reg1, $password,$result1);
	        preg_match($reg2, $password,$result2);
	        preg_match($reg3, $password,$result3);
	        preg_match($reg4, $password,$result4);
	        
	        if($result1 || $result2){
	            //说明是纯字母或纯数字的
	            $this->error[] = '纯字母或数字的简单爆了';
	            return true;
	        }elseif ($result3){
	            //说明是字母、数字的组合
	            $this->error[] = '密码安全性一般';
	            return true;
	        }elseif($result4){
	            //说明是特殊符号、字母、数字的组合
	            $this->error[] = '密码杠杠滴';
	            return true;
	        }else{
	            $this->error[] = '密码不符合规则';
	            return false;
	        }
	    }
	    //验证邮箱是否符合规则
	    //参数1：$email，待验证的邮箱
	    public function checkEmail($email){
	        //@后面可能有vip.  也可能没有
	        $reg = '/^[\w\-\.]+@([a-zA-Z\d]+\.)?[a-zA-Z\d]+\.[A-Za-z]{2,4}$/';
	                
	        //3. 使用我们规则检验一下用户的输入是否符合规则
	        preg_match($reg, $email,$result);
	        if($result){
	            //验证通过
	            return true;
	        }else{
	            //邮箱不合法
	            $this->error[] = '邮箱不合法';
	            return false;
	        }
	    }
	}
?>