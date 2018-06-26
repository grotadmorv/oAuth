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
        $token = $this->DBManager->getAllTokens();
        // var_dump($_GET["auth_token"]);
        // var_dump(array_values($token));
        // var_dump(in_array($_GET["auth_token"], $token));
        if((in_array($_GET["auth_token"], $token))) {
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
        //TODO insert token into DB
        return json_encode($res);
    }

}
