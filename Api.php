<?php
    spl_autoload_register(function ($class_name) {
        $appFolder = 'app';
        if (isset($GLOBALS['request']) && null !== $GLOBALS['request']->getApplicationId())
            $appFolder = $GLOBALS['request']->getApplicationId();

        $GLOBALS['appFolder'] = $appFolder;

        if (file_exists($class_name . '.php')) {
            include $class_name . '.php';
        } elseif (file_exists('domain/' . $class_name . '.php')) {
            include 'domain/' . $class_name . '.php';
        } elseif (file_exists($appFolder . '/' . $class_name . '.php')) {
            include $appFolder . '/' . $class_name . '.php';
        }
    });

    if( count(debug_backtrace()) == 0 ) {
        $json = file_get_contents('php://input');
        file_put_contents('/var/www/html/input.log', $json . PHP_EOL, FILE_APPEND);
    }
    $request = new RequestEnvelope();
    $request = Utils::json_decode_full($json, $request);
    $GLOBAL['request'] = $request;

    $before = get_declared_classes();
    foreach (glob("$appFolder/*.php") as $filename)
        include $filename;
    $after = get_declared_classes();
    $appClasses = array_diff($after, $before);


    $handler = new Handler($request, $appClasses);
    $handler->Run();
?>
