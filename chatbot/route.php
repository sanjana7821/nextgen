<?php 

$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH);

switch ($request) {
    case '/':
    case '/index':
        require __DIR__ . '/src/views/index.php';
        break;

    case '/app_chat.php':
        require __DIR__ . '/routes/app_chat.php';
        break;

    // Handle static assets (CSS/JS)
    default:
        $file = __DIR__ . '/src' . $request;
        if (file_exists($file) && !is_dir($file)) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $mimes = [
                'css' => 'text/css',
                'js'  => 'application/javascript',
            ];
            if (isset($mimes[$extension])) {
                header("Content-Type: " . $mimes[$extension]);
            }
            readfile($file);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
        break;
}