<?php

namespace Controller;

use Model\DBManager;
use GuzzleHttp\Guzzle;

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
        $tokens = $this->DBManager->getAllTokens();

        $token = null;
        foreach($tokens as $tokenArray) {
            if ($tokenArray['value'] == $_GET["auth_token"]) {
                $token = true;
                break;
            }
        }

        if($token) {
            require('Web/views/auth_form.php');
        } else {
            echo "token required";
        }

    }

    public function authTokenAction() {
        $bytes = random_bytes(255);
        $token = bin2hex($bytes);

        $this->DBManager->insert('token', array('user_id' => NULL, 'type' => 'auth', 'value' => $token, 'callback_url' => $_POST['url']));

        $res = array('auth_token'=>$token, 'form_url'=>"https://sup-auth.herokuapp.com/?action=form&auth_token=$token");

        echo json_encode($res);
    }

    public function confirmFormAction()
    {
        $auth_token = $this->DBManager->getWhatHow($_POST['auth_token'], 'value', 'token');
        if(isset($_POST['auth_token']) && $_POST['auth_token'] == $auth_token[0]['value'] ){
            if(!empty($_POST['email'] && $_POST['password'])){
                $user = $this->DBManager->getWhatHow($_POST['email'], 'email', 'user');
                //todo check password is ok

                if (!password_verify($_POST['password'], $user[0]['password'])) {
                    echo "Wrong password";
                    exit();
                }

                if($user){
                    $bytes = random_bytes(255);
                    $token = bin2hex($bytes);
                    $this->DBManager->insert('token', array('user_id' => $user[0]['id'], 'type' => 'confirm', 'value' => $token));
                    $this -> DBManager -> dbSuppress("token", $auth_token[0]['id']);

                    header ('Location: '. $auth_token[0]['callback_url'] . '&confirm_token='. $token);
                }else{
                    echo "no";
                }
            }
        }
    }

    public function accessTokenAction() {
        $confirmToken = $_GET['confirm_token'];
        $bytes = random_bytes(255);
        $token = bin2hex($bytes);
        $tokens = $this->DBManager->getWhatHow($confirmToken,'value', 'token');
        if (count($tokens) == 0) {
            return json_encode(array(
                'status' => 'error',
            ));
        }
        echo $tokens;
        die;
        $res = array('access_token'=>$token);
        return json_encode($res);
    }

    public function secretTokenAction() {
        $accessToken = $_POST['access_token'];
        $tokens = $this->DBManager->getWhatHow($accessToken,'value', 'token');

        if (count($tokens) == 0) {
            return json_encode(array(
                'status' => 'error',
            ));
        }
        $user = $this->DBManager->getWhatHow($tokens[0]['user_id'], 'id', 'user');
        if (count($user) == 0) {
            return json_encode(array(
                'status' => 'error',
            ));
        }
        $res = array('secret_token'=>$user[0]["secret"]);

        return json_encode($res);
    }
}
