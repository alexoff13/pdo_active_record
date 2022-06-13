<?php
require_once('../vendor/autoload.php');

$is_remove = \Worker\Worker::remove($_GET['id']);
if ($is_remove) {
    echo '<h1>The record is successful</h1>';
} else {
    echo '<h1>An error has occurred. The recording is not deleted</h1>';

}