<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
require_once PROJECT_ROOT_PATH . "/Utils/jwt_utils.php";

class UserModel extends Database
{
    public function getUsers($userID)
    {
        return $this->select("users", $userID);
    }
	
    public function getOnlineUsers()
    {
		$conditions = array("isOnline" => 1);
		$userData = $this->select("users", $conditions);
        return $userData;
    }
	
	public function signinUser($userName, $email)
	{
		$userData = $this->select("users", array("name"=>$userName, "email"=>$email));
		
		$jwt = '';
		$message = '';
		if($userData)
		{
			$userData = array_pop($userData);
			//update user online
			$this->updateUserOnline($userData);
			$headers = array('alg'=>'HS256','typ'=>'JWT');
			$payload = array('username'=>$userName, 'exp'=>(time() + 60));

			$jwt = generate_jwt($headers, $payload);
		}
		else 
		{
			$message = 'Incorrect Username or Email.';
		}
		$retData = array('token' => $jwt, 'user' => $userData, 'message' => $message);
		return json_encode($retData);
	}
	
	public function updateUserOnline($userData)
	{
		$userID = $userData['id'];
		$visits_count = $userData['visits_count'];
		$data = array("isOnline" => 1, 
						"visits_count" => ++$visits_count,
						"entrance_time" => date("d/m/Y H:i"),
						"last_update_time" => date("d/m/Y H:i"));
		$this->updateUser($userID, $data);
	}
	
	public function logoutUser($userID)
	{
		$jwt = '';
		$message = 'failed';
		if($userID)
		{
			//update user offline
			$this->updateUserOffline($userID);
			$message = 'success';
		}
		$retData = array('message' => $message);
		return json_encode($retData);
	}
	
	public function updateUserOffline($userID)
	{
		$data = array("isOnline" => 0, 
						"last_update_time" => date("d/m/Y H:i"));
		$this->updateUser($userID, $data);
	}
	
	public function updateUser($userID, $data)
	{
		$this->update("users", $data, $userID);
	}
}
?>