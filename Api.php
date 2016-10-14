<?php
    spl_autoload_register(function ($class_name) {
        if (file_exists($class_name . '.php'))
        {
            include $class_name . '.php';
        } elseif (file_exists('domain/' . $class_name . '.php'))
        {
            include 'domain/' . $class_name . '.php';
        } elseif (file_exists('app/' . $class_name . '.php'))
        {
            include 'app/' . $class_name . '.php';
        }
    });

    if( count(debug_backtrace()) == 0 ) 
        $json = file_get_contents('php://input');
    $request = new RequestEnvelope();
    $request = Utils::json_decode_full($json, $request);
    $handler = new Handler($request);
    $handler->Run();
?>