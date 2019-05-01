<?php

	include './config.php';
	include './Model.class.php';
	
	$info = new Model('info');
	
	var_dump($_GET);
	
	//$_GET['a']代表你操作的方法是什么
	
	$a = $_GET['a'];
	
	switch($a){
		case 'add':
			if($info ->add($_POST)){
				echo "成功";
			}else{
				echo "失败";
			}
			break;
		case 'del':
			if($info ->delete($_GET['id'])){
				echo "成功";
			}else{
				echo "失败";
			}
			break;
		case 'edit':
			if($info ->update($_POST)){
				echo "成功";
			}else{
				echo "失败";
			}
			break;
	}