<?php
require_once('../vendor/autoload.php');
$loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');
$view = new \Twig\Environment($loader);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo $view->render('worker_create.html.twig');
} else {
    $worker = new \Worker\Worker($_POST['name'], $_POST['address'], $_POST['salary']);
    if ($worker->add()) {
        echo '<h1>Record is successfully added</h1>';
    } else {
        echo '<h1>Something went wrong</h1>';
    }
}