<?php

namespace App\Controller;

use App\Model\ConnectDB;
use App\Model\PDOModel;
use App\Model\UserModel;
use App\Routes\HttpRequest;

class UserController extends MainController
{
    /**
     * Login page
     * @return Render View
     */

    public function loginPage()
    {
        return $this->view('userConnection/login.twig');
    }

    /**
     * SignUp page
     * @return Render View
     */

    public function signUpPage()
    {
        return $this->view('userConnection/signUp.twig');
    }


    /**
     * SignUp user
     * @param Object $requestForPost
     * @return void
     */

    public function signUp(HttpRequest $requestForPost)
    {
        $data = $requestForPost->valueForm();

        try {
            $this->verifyInputName($data['user_name']);
            $this->verifyInputEmail($data['mail']);
            $this->verifyInputPassword($data['password']);
            $userModel = new UserModel(new PDOModel(ConnectDB::getPDO()));
            $data['password'] =  password_hash($data['password'], PASSWORD_DEFAULT);
            $user_mail = $userModel->getUser($data['mail']);
            if ($user_mail->getMail()) {
                throw new \Exception('User exist.');
            }
            $userModel->signUpUser($data['user_name'], $data['mail'], $data['password']);
            echo json_encode(array("success" => true));
        } catch (\Exception $e) {
            echo json_encode(array("error" => $e->getMessage()));
        }
    }

    /**
     * Login user
     * @param Object $requestForPost
     * @return void
     */

    public function login(HttpRequest $requestForPost)
    {
        $data = $requestForPost->valueForm();
        try {
            $this->verifyInputEmail($data['mail']);
            $this->verifyInputPassword($data['password']);
            $userModel = new UserModel(new PDOModel(ConnectDB::getPDO()));
            $data_user = $userModel->getUser($data['mail']);
            if (!$data_user->getMail()) {
                throw new \Exception('Email user doesn\'t exist.');
            }

            if (!password_verify($data['password'], $data_user->getPassword())) {
                throw new \Exception('Password user doesn\'t valid.');
            }

            $this->session->createSession($data_user);

            echo json_encode(array("success" => true));
        } catch (\Exception $e) {
            echo json_encode(array("error" => $e->getMessage()));
        }
    }

    /**
     * Logout user and redirect to home page
     */

    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        return $this->redirect('/');
    }
}
