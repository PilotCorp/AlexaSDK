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
    $before = get_declared_classes();
    foreach (glob("app/*.php") as $filename)
    {
        include $filename;
    }
    $after = get_declared_classes();
    $appClasses = array_diff($after, $before);

    if( count(debug_backtrace()) == 0 ) {
        $json = file_get_contents('php://input');
        file_put_contents('/var/www/html/input.log', $json . PHP_EOL, FILE_APPEND);
    }
    $request = new RequestEnvelope();
    $request = Utils::json_decode_full($json, $request);
    $handler = new Handler($request, $appClasses);
    $handler->Run();
?>
