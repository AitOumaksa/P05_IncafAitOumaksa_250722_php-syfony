<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use App\Controller\Globals\SessionController;

//Main is calss Parent

class MainController
{
    /**
     * @var $session
     */

    protected $session;

    public function __construct()
    {
        $this->session = new SessionController();
    }

    /**
     * Setting twig
     * @param String $path
     * @param Array $datas
     * @return void
     */

    public function view(string $path, array $datas = [])
    {
        $loader = new FilesystemLoader('src/View');
        $twig = new Environment($loader, [
            'cache' => false,
        ]);
        $twig->addGlobal('session', $_SESSION);
        echo $twig->render($path, $datas);
    }



    /**
     * Redirect methode
     * @param String $page
     * @return void
     */

    public function redirect(string $page)
    {
        header('Location: http://localhost' . $page);
    }

    /**
     * To display home page
     * @return void
     */

    public function afficheHome()
    {
        $this->view('home.twig');
    }

    /**
     * Check input mail
     * @param String $email
     * @return BOOL
     */

    public function verifyInputEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Email not valid.');
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check input name
     * @param String $name
     * @return BOOL
     */

    public function verifyInputName(string $name)
    {
        if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
            throw new \Exception('Name format not valide or empty.');
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check input password
     * @param String $password
     * @return BOOL
     */

    public function verifyInputPassword(string $password)
    {
        if (!preg_match("/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,64})/", $password)) {
            throw new \Exception('le mot de passe doit contenir 8 caractères dont au minimum une majuscule, une minuscule, un caractère numérique et un caractère spécial.');
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check input message
     * @param String $message
     * @return BOOL
     */

    public function verifyInputMessage(string $message)
    {
        if (empty(htmlspecialchars($message))) {
            throw new \Exception('input empty or format not valid');
            return false;
        } else {
            return true;
        }
    }
}
