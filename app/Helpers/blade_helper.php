<?php

use Jenssegers\Blade\Blade;


function view_blade($view, $data = []) {
    $path  = __DIR__ . '/../Views';
    $blade = new Blade($path, $path.'/cache');
    echo $blade->make($view, $data)->render();
}