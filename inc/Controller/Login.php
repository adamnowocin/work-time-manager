<?php

namespace Controller;

use Core\View;
use Core\Database;
use Core\Request;
use Core\Session;
use Model\User;

class Login
{

    public function View()
    {
        $login = Request::Field('login');
        $pass = Request::Field('pass');

        if ($login && $pass) {
            $userData = (new User)->First(array(
                'login' => $login,
                'pass' => Database::GetHash($pass)
            ));
            if (isset($userData['id'])) {
                Session::Set('uid', $userData['id']);
                Request::Location(HOME_LOCATION);
            }
        }

        View::Load('login');
    }

}
