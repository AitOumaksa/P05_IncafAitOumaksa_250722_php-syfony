<?php

namespace App\Controller\Globals;

use App\Model\UserTable;

class SessionController
{
    public const ADMIN = 'Admin';

    public const USER = 'User';

    /**
     * @var $session
     */

    private $session;

    /**
     * @var $user
     */

    private $user;

    public function __construct()
    {
        $this->session = filter_var_array($_SESSION);
        if (isset($this->session['user'])) {
            $this->user = $this->session['user'];
        }
    }

    /**
     * Creating a session user
     * @param  Object $data
     *  @return void
     */

    public function createSession(UserTable $data)
    {
        if ($data->getIsAdmin() == '1') {
            $data->setIsAdmin(self::ADMIN);
        } elseif ($data->getIsAdmin() == '0') {
            $data->setIsAdmin(self::USER);
        }

        $this->session['user'] = [
            'sessionId' => session_id(),
            'id' => $data->getId(),
            'user_name' => $data->getUserName(),
            'mail' => $data->getMail(),
            'createdAt' => $data->getCreatedAt(),
            'updatedAt' => $data->getUpdatedAt(),
            'is_admin' => $data->getIsAdmin()
        ];
        $this->user = $this->session['user'];

        $_SESSION['user'] = $this->session['user'];
    }

    /**
     * Getting a variable of user
     * @param  String  $var
     * @return Mixed $var
     */

    public function getUserVar($var)
    {
        if (!empty($this->user[$var])) {
            return $this->user[$var];
        }
        return false;
    }

    /**
     * virifier if user is logged
     * @return Bool 
     */

    public function isLogged()
    {
        if (!empty($this->getUserVar('sessionId'))) {
            return true;
        }
        throw new \Exception(' you can\'t add comment , you need connected ');
        return false;
    }
}
