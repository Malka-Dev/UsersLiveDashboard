 <?php
class Database{
  
	private $db_dir = __DIR__ . '/db/';
	
	//The db data array
	private $db_data = [];
	
	public function __construct() {}
	
		//Insert data in the table file
	public function insert($table, $new_data){
		$this->load_table($table);

		if(!empty($new_data)){
			$id = $this->get_unique_id();
			$this->db_data[$table][$id] = $new_data;
			if($this->write_to_db($table)){
				return $id;
			}
			return false;
		}
		return false;
	}
	
	private function get_unique_id(){
		return md5($_SERVER['REQUEST_TIME'] + mt_rand(1000,9999));
	}
	
	//Select data by its key
	public function select($table, $condition = null){
		$this->load_table($table);
			
		//no condition 
		if (!$condition) {
			return $this->db_data[$table];
		}
		//array condition
		if(is_array($condition)){
			$data = [];
			foreach($this->db_data[$table] as $k => $v){
				foreach($condition as $condition_key => $condition_value){
					if(!isset($v[$condition_key]) || $v[$condition_key] != $condition_value){
						continue 2;
					}
				}
				$data[$k] = $v;
			}
			return $data;
		}else{
			//id condition
			return isset($this->db_data[$table][$condition]) ? [$condition => $this->db_data[$table][$condition]] : false;
		}
	}

	private function write_to_db($table){
		if(!isset($this->db_data[$table])){
			return file_put_contents($this->get_table_path($table),'');
		}
		return file_put_contents($this->get_table_path($table),json_encode($this->db_data[$table]));
	}
	
	//Delete content by id
	public function delete($table, $id = null){
		$this->load_table($table);

		if(!$id){
			return $this->delete_all($table);
		}else{
			if(isset($this->db_data[$table][$id])){
				unset($this->db_data[$table][$id]);
				return $this->write_to_db($table);
			}
		}
		return false;
	}

	//Delete all contents of table
	public function delete_all($table) {
		$this->set_table($table);
		unset($this->db_data[$table]);
		return $this->write_to_db($table);
	}

	//Update content by id
	public function update($table, array $data, $id){
		$this->load_table($table);

		if(isset($this->db_data[$table][$id])){
			$this->db_data[$table][$id] = array_merge(
				$this->db_data[$table][$id],
				$data
			);
			if($this->write_to_db($table)){
				return $this->db_data[$table];
			}else{
				return false;
			}
		}
		return false;
	}

	//Get file directory path
	private function get_table_path($table) {
		return $this->db_dir . '/' . $table . '.txt';
	}

	//Load table data
	private function load_table($table) {
		if(!isset($this->db_data[$table])){
			if(!is_file($this->get_table_path($table))){
				$this->db_data[$table] = [];
			}else{
				$this->db_data[$table] = json_decode(file_get_contents($this->get_table_path($table)),true);
			}
		}
		return $this->db_data[$table];
	}
    /*// specify your own database credentials
    private $host = "localhost";
    private $db_name = "api_db";
    private $username = "root";
    private $password = "";
    public $conn;
  
    // get the database connection
    public function getConnection(){
  
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }*/
}
?>