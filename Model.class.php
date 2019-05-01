<?php
	
	//数据库操作类
	class Model{
		public $link;//连接数据库
		public $tabName;//数据表
		public $fields='*';//存储字段名
		public $sql;//用来存储上一次操作的sql语句
		public $allFields;//用来存储数据库字段
		public $limit;//每页显示多少条
		public $order;//排序
		public $where;//查询条件
		
		//构造方法
		public function __construct($tabName)
		{
			//连接数据库
			$this ->getConnect();
			
			//初始化数据表
			$this ->tabName = $tabName;
			
			//获取数据库字段
			$this ->getFields();
		}
		
		public function add($data=array())
		{
			//1.得到字段列表
			//1.1 获取数组中的键作为我们sql语句的字段
			$key = array_keys($data);
			
			
			//1.2 将得到 的 键名之后的数组变为字符串
			$keys = join('`,`',$key);
			
			
			//2.得到添加的值
			$value = array_values($data);
			
			
			
			//2.2 将 得到 的数组拼接成字符串
			
			$values = join("','",$value);
		
			$sql="INSERT INTO {$this->tabName}(`{$keys}`) VALUES('{$values}')";
			//echo $sql;exit;
			return $this->execute($sql);
		}
		
		public function delete($id='')
		{
			if(empty($id)){
				$where = $this->where;
			}else{
				$where = ' WHERE id='.$id;
			}
			if(empty($where)){
				return '请输入条件';
			}
			
			$sql = "DELETE FROM {$this->tabName} {$where}";
			return $this->execute($sql);
			
			
		}
		
		
		public function update($data=array())
		{
			var_dump($data);
			//判断数组是否为空判断是否是数组
			if(!is_array($data) || empty($data))
			{
				return false;
			}
			//判断你是否使用id作为修改条件  使用id则有值 如果不是使用id则id没有值
			if(empty($data['id']))
			{
				//使用where条件修改
				$where = $this->where;
			}else{
				//用id作为修改条件
				$where = " WHERE id='{$data['id']}' ";
			}
			if(empty($where)){
				return false;
			}
			
			
			//将我们传递过来的数组让他们键和值拼接在一起
			$result ='';
			foreach($data as $key=>$value)
			{
				//$result = $result."{$key}='{$value}',";
				if($key !='id'){
					$result .= "`{$key}`='{$value}',";
				}
			}
			//将多出来的逗号去掉
			$result = rtrim($result,',');
			
			$sql= "UPDATE {$this->tabName} SET {$result} {$where}"
			;
			$bool = $this->execute($sql);
			return $bool;
		}
		
	
		
		//查询单条数据
		public function find($id)
		{
			$sql="SELECT * FROM {$this->tabName} WHERE id={$id}";
			$userlist = $this->query($sql);
			return $userlist[0];
		}
		//统计 总条数
		public function count()
		{
			$sql="SELECT COUNT(*) as total FROM {$this->tabName}{$this->where}";
			$userlist = $this->query($sql);
			var_dump($userlist);
			return $userlist[0]['total'];
		}
		//查询方法
		public function select()
		{
			$sql="SELECT {$this->fields} FROM {$this->tabName} {$this->where} {$this->order}  {$this->limit}";
			return $this->query($sql);
			
		}
		
		//字段筛选
		public  function field($fields=array())
		{
			//var_dump($fields);
			//判断fields 是否是数组
			if(!is_array($fields)){
				return $this;
			}
			//检测数据库内容删除没有的字段
			$fields = $this->check($fields);
			
			if(empty($fields)){
				return $this;
			}
			
			//拼接字符串得到我们想要的内容
			$this->fields = join(',',$fields);
			return $this;
		}
		//每页显示多少条
		public  function limit($limit)
		{
			//var_dump($limit);
			$this->limit = " LIMIT {$limit}";
			return $this;
		}
		//写order by
		//字段名  ASC |DESC
		public function order($order){
			$this->order = " ORDER BY {$order} ";
			return  $this;
		}
		
		public function where($data = array())
		{
			//var_dump($data);
			//1.判断$data是否是数组 而且这个数组不能为空
			if(is_array($data) && !empty($data)){
				//说明不是空的数组
				foreach($data as $key=>$value){
					if(is_array($value)){
						//var_dump($value);
						switch($value[0]){
							case 'like':
								$result[]=" `{$key}` like '%{$value[1]}%' ";
							
								break;
							case 'lt':
								$result[]=  " `{$key}` < '{$value[1]}'";
								break;
							case 'gt':
								$result[] = "`{$key}` > '{$value[1]}'";
								break;
							case 'in':
								$result[]= "`{$key}` in({$value[1]})";
								break;
						}
						
					}else{
						//不是数组就是简单的等于关系
						$result[] =  "`{$key}`='{$value}'";
					}
				}
				//var_dump($result);exit;
				$where = ' WHERE '.join(' and ',$result);
				$this->where = $where;
			}else{
				return $this;
			}
			
			return $this;
		}
		
		
		/*****************辅助方法**********************/
		//检测字段是否在数据库中
		public function check($arr)
		{
			var_dump($arr);
			//传递过来的数组需要拿出里面所有的 值 将值和我们刚刚得到数据库字段数组进行比较如果有留下如果没有删除
			foreach($arr as $key=>$value)
			{
				//echo $value;
				if(!in_array($value,$this->allFields)){
					unset($arr[$key]);
				}
			}
			
			return $arr;
		}
		//增删改的发送语句方法
		public  function execute($sql)
		{
			$this->sql = $sql;
			$result = mysqli_query($this->link,$sql);
			if($result && mysqli_affected_rows($this->link)>0)
			{
				//判断是否是添加操作 如果有值说明你做的是添加操作
				if(mysqli_insert_id($this->link)){
					return mysqli_insert_id($this->link);
				}
				return true;
			}else{
				return false;
			}
		}
		
		//查询操作的方法
		public function query($sql)
		{
			$this->sql=$sql;
			$result= mysqli_query($this->link,$sql);
			if($result && mysqli_num_rows($result)>0){
				$userlist = array();
				while($row = mysqli_fetch_assoc($result)){
					$userlist[]=$row;
				}
				return $userlist;
			}
		}
		//获取数据库字段 用来做缓存字段
		public  function getFields()
		{
			//查看表信息的数据库语句我们查询即可
			$sql="DESC {$this->tabName}";
			
			//发送
			$result = $this->query($sql);
			//var_dump($result);
			//新建一个数组用来存储我们的字段
			$fields = array();
			foreach($result as $value){
				//var_dump($value['Field']);
				$fields[] = $value['Field'];
			}
			//var_dump($fields);
			$this->allFields = $fields;
		}
		
		//连接数据库
		public function getConnect()
		{
			$this->link = mysqli_connect(HOST,USER,PWD);
			if(mysqli_connect_errno($this->link)>0){
				echo mysqli_connect_error($this->link);exit;
			}
			mysqli_select_db($this->link,DB);
			
			mysqli_set_charset($this->link,CHARSET);
		}
		
		
		public function __destruct()
		{
			mysqli_close($this->link);
		}
	
	}