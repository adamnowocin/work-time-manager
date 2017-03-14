<?php

namespace Controller;

use Core\View as View;

class Login
{
    public function View() {
        View::Load('login');
    }
}