<?php

namespace Controller;

use Core\View as View;

class Index
{
    public function View() {
        View::Load('index');
    }
}