<?php


namespace Controller;


use Model\DBManager;

class UserController {

	private $DBManager;
	/**
	 * UserController constructor.
	 */
	public function __construct() {
		$this->DBManager = DBManager::getInstance();
	}

	public function registerAction(){
		$error = '';
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->user_register($_POST, ['email', 'password', 'secret']);
		}else{
			require('Web/views/register.php');
		}
	}

	private function user_hash($pass){
		return password_hash($pass, PASSWORD_BCRYPT);
	}
	private function transform_data($data){
		$data['password'] = $this->user_hash($data['password']);
		return $data;
	}
	private function user_register($data, $arrayFields){
		$data = $this->transform_data($data);//currently useless (function with only one instruction... But allows easier improvement if in the future one want to add other
		// transformation to data before inscription in db.
		foreach ($arrayFields as $field) {
			$user[$field] = $data[$field];
		}
		$this->DBManager->insert('user', $user);
	}
}