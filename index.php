<?php
	
	include './config.php';
	include './Model.class.php';
	$info = new Model('info');
	$userlist = $info ->select();
	$i = 1;
	//car_dump($userlist);
	
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>DD</title>
	</head>
	<body>
		<table border="1" width="800" align="center">
		<tr>
			<th>编号</th>
			<th>姓名</th>
			<th>年龄</th>
			<th>性别</th>
			<th>城市</th>
			<th>操作</th>
		</tr>
		<?php foreach($userlist as $value){?>
		<tr>
			<td><?php echo $i++;?></td>
			<td><?php echo $value['name']?></td>
			<td><?php echo $value['age']?></td>
			<td>
			
				<?php 
				
					$sex=$value['sex'];
					switch($sex){
						case 0:
							echo '女';
							break;
						case 1:
							echo '男';
							break;
						case 2:
							echo '保密';
							break;
						default:
							echo '其他';
					}
				
				?>
			
			
			</td>
			<td><?php echo $value['city']?></td>
			<td><a href="./edit.php?id=<?php echo $value['id']?>">编辑</a>|<a href="./action.php?a=del&id=<?php  echo $value['id']?>">删除</a></td>
		</tr>
		<?php } ?>

	</table>
	</body>
</html>