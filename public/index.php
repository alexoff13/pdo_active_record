<?php
require_once('../vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();


$loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');
$view = new \Twig\Environment($loader);

$workers = \Worker\Worker::getAll();

echo $view->render('workers.html.twig', ['workers' => $workers]);
