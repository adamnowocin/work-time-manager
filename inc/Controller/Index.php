<?php

namespace Controller;

use Core\View;

class Index
{
    public function View() {
        View::Load('index');
    }
}