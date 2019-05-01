<?php

	include './config.php';
	
	include './Model.class.php';
	
	$info = new Model('info');
	
	
	//var_dump($_GET);
	
	if($info ->delete($_GET['id'])){
		echo "删除成功";
	}else{
		echo "删除失败";
	}