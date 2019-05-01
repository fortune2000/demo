<?php
	var_dump($_GET);
	include './config.php';
	include './Model.class.php';
	$info = new Model('info');
	$user = $info->find($_GET['id']);
	var_dump($user);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="action.php?a=edit" method="post">
		<input type="hidden" name="id" value="<?php echo $user['id']?>">
		姓名: <input type="text" name="name" value="<?php echo $user['name']?>"><br>
		年龄: <input type="text" name="age" value="<?php echo $user['age']?>"><br>
		性别: 
			<input type="radio" name="sex" value="0" <?php echo $user['sex']==0?'checked':''?>>女
			<input type="radio" name="sex" value="1" <?php echo $user['sex']==1?'checked':''?>>男
			<input type="radio" name="sex" value="2" <?php echo $user['sex']==2?'checked':''?>>浩哥
		<br>
		城市: <input type="text" name="city"  value="<?php echo $user['city']?>"><br>
		 <input type="submit" value="修改"><br>
	</form>
</body>
</html>