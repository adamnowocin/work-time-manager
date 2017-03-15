<?php

namespace Controller;

use Core\View;

class Login
{
    public function View() {
        View::Load('login');
    }
}