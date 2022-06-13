<?php
require_once('../vendor/autoload.php');
$loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');
$view = new \Twig\Environment($loader);
if (isset($_GET['id'])) {
    $worker = \Worker\Worker::getByID($_GET['id']);
    if ($worker) {
        echo $view->render('worker_update.html.twig', ['worker' => $worker]);

        if (isset($_POST['name'])) {
            $worker->setName($_POST['name']);
            $worker->setAddress($_POST['address']);
            $worker->setSalary($_POST['salary']);
            if ($worker->save()) {
                echo '<h1>The data is successfully changed</h1>';
            } else {
                echo '<h1>Something went wrong</h1>';
            }
        }
    } else {
        echo '<h1>An error has occurred. ID is not found</h1>';
    }
}
