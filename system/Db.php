<?php
$db['default'] = array(
	'host'     => 'localhost',
	'username' => 'root',
	'password' => 'toor',
	'database' => 'native'
);
Class Db
{
	var $mysqli_protected;
	var $insert_id = 0;
	var $data = array();
	public function __construct($db)
	{
		if(!empty($db) && is_array($db))
		{
			$db = $db['default'];
			$error = array();
			if(empty($db['host']))
			{
				$error[] = 'host is undefined';
			}
			if(empty($db['username'])){
				$error[] = 'username is undefined';
			}if(empty($db['database'])){
				$error[] = 'database is undefined';
			}

			if(!empty($error))
			{
				foreach ($error as $key => $value)
				{
					echo '<p style="color: red;">'.$value.'<p>';
				}
			}else{
				mysqli_report(MYSQLI_REPORT_STRICT);
				try{
					$param = new mysqli($db['host'],$db['username'],$db['password'], $db['database']);
				}catch(Exception $e){
					echo '<p style="color: red;">'.$e->getMessage().'</p>';
					$param = array();
				}
				
				$this->mysqli_protected = $param;
			}
		}
		return $this->mysqli_protected;
	}

	public function query($q = '')
	{
		$data = array();
		if(!empty($q))
		{
			$data            = $this->mysqli_protected->query($q);
			$this->data      = $data;
			$this->insert_id = @$this->mysqli_protected->insert_id;
			echo $this->mysqli_protected->error."\n";
			return $data;
		}
	}

	public function result()
	{
		$data = array();
		if(!empty($this->data))
		{
			while($row = $this->data->fetch_assoc())
			{
				$data[] = $row;
			}
			return $data;
		}
	}
}
$db = new db($db);