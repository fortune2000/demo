<?php

	//引入配置文件config.php
	
	include './config.php';
	include './Model.class.php';
	
	$info = new Model('info');
	if($info ->add($_POST)){
		echo '添加成功';
	}else{
		echo '添加失败';
	}