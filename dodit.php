<?php
	
	var_dump($_POST);
	include './config.php';
	include './Model.class.php';
	
	$info = new Model('info');
	
	$bool = $info ->update($_POST);
	if($bool){
		echo '修改成功';
	}else{
		echo '修改失败';
	}