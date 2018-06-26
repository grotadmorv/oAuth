<?php

namespace Controller;

use Model\DBManager;

class DefaultController
{
	private $DBManager;

	/**
	 * DefaultController constructor.
	 *
	 * @param $DBManager
	 */
	public function __construct() {
		$this->DBManager = DBManager::getInstance();
	}


	public function homeAction()
    {
        echo "Vous êtes nuls =) Zoubi Nathou";
    }

    function dbTestAction() {
        $user = $this->DBManager->getWhatHow(1, 'id', 'user');
        var_dump($user);
    }

    public function formAction()
    {
        if($_GET["auth_token"]) {
            require('Web/views/auth_form.php');
        } else {
            echo "token required";
        }

    }

    public function  authTokenAction() {
        $bytes = random_bytes(255);
        $token = bin2hex($bytes);

				$this->DBManager->insert('token', array('user_id' => NULL, 'type' => 'auth', 'value' => $token));

        $res = array('auth_token'=>$token, 'form_url'=>"https://sup-auth.herokuapp.com/?action=form&auth_token=$token");

        return json_encode($res);
    }

}
