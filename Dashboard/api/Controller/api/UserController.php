<?php
class UserController extends BaseController
{
	/**
     * "/user/signin" Endpoint - Signin user
     */
	public function signinAction()
	{
		$strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            try {
                $userModel = new UserModel();
				$postdata = json_decode(file_get_contents("php://input"));
				$userName = !empty($postdata->userName) ? $postdata->userName : '';
				$email = !empty($postdata->email)? $postdata->email : '';
                $userData = $userModel->signinUser($userName, $email);
                $responseData = json_encode($userData);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
	}
	
	/**
     * "/user/logout" Endpoint - Logout user
     */
	public function logoutAction()
	{
		$strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            try {
				$bearer_token = get_bearer_token();

				$is_jwt_valid = is_jwt_valid($bearer_token);
				if($is_jwt_valid)
				{
					$ret = array();
					$userModel = new UserModel();
					$postdata = json_decode(file_get_contents("php://input"));
					$userID = !empty($postdata->id) ? $postdata->id : null;
					if($userID)
					{
						$ret = $userModel->logoutUser($userID);
					}
					$responseData = json_encode($ret);
				}
				else 
				{
					$strErrorDesc = 'Access denied';
					$strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
				}
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
			
			try {
				
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
	}
	
    /**
     * "/user/list" Endpoint - Get list of users
     */
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($requestMethod) == 'GET' || strtoupper($requestMethod) == 'OPTIONS') {
            try {
				$bearer_token = get_bearer_token();

				$is_jwt_valid = is_jwt_valid($bearer_token);
				if($is_jwt_valid)
				{
					$userModel = new UserModel();
					$userID = null;
					if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
						$userID = $arrQueryStringParams['id'];
						$arrUsers = $userModel->getUsers($userID);
					}
					else 
					{
						$arrUsers = $userModel->getOnlineUsers();
					}
					$responseData = json_encode($arrUsers);
				}
				else 
				{
					$strErrorDesc = 'Access denied';
					$strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
				}
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}