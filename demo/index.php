<?php
include __DIR__ . '/../vendor/autoload.php';

use Puja\Paginator\Paginator;
$paginator = new Paginator('/puja-paginator/demo/', 100, 10);
echo $paginator->render('simple');
