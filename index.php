<?php

@require('bootstrap.php');

if (Core\Request::Field('logout')) {
    (new Controller\User)->Logout();
} else {
    (new Controller\Index)->View();
}

Core\View::Render();