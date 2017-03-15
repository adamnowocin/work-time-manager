<?php

namespace Controller;

use Core\Request;

class User
{
    public function Logout() {
        Request::Location(HOME_LOCATION);
    }
}
