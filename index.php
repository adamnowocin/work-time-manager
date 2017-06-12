<?php

@require('bootstrap.php');

if (Core\Request::Field('logout')) {
    (new Controller\User)->Logout();
}

if (!Core\Session::Get('uid')) {
    (new Controller\Login)->View();
} else {
    (new Controller\Index)->View();
}

Core\View::Render();