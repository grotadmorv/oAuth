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
        $token = $this->DBManager->getAllTokens();
        // var_dump($_GET["auth_token"]);
        // var_dump(array_values($token));
        // var_dump(in_array($_GET["auth_token"], $token));
        // if((in_array($_GET["auth_token"], $token))) {
        if($_GET["auth_token"]) {
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
                if($user){
                    $bytes = random_bytes(255);
                    $token = bin2hex($bytes);
                    $this->DBManager->insert('token', array('user_id' => $user[0]['id'], 'type' => 'confirm', 'value' => $token));
                    $this -> DBManager -> dbSuppress("token", $auth_token[0]['id']);

                    $response = $client->request('POST', $auth_token[0]['callback_url'], [
                        'form_params' => [
                            'confirm_token' => $token,
                        ]
                    ]);
                }else{
                    echo "no";
                }
            }
        }
    }

    public function accessTokenAction() {
        $confirmToken = $_POST['confirm_token'];
        $bytes = random_bytes(255);
        $token = bin2hex($bytes);
        $tokens = $this->DBManager->getWhatHow($confirmToken,'value', 'token');
        if (count($tokens) == 0) {
            return json_encode(array(
                'status' => 'error',
            ));
        }
        $res = array('access_token'=>$token);
        return json_encode($res);
    }

}
