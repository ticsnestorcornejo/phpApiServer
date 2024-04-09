<?php
include_once 'dominio.php';
include_once 'controller_generator.php';
include_once 'main_generator.php';

$tables = DB::xos_getTables()["tables"];

$pathControllers = "../controllers/";
$pathMains = "../main/";


mkdir($pathControllers, 0777);
mkdir($pathMains, 0777);


$controlador = new CONTROLER_DATABASE_GENERATOR();
$controlador->setPath($pathControllers);
$controlador->setTables($tables);
$controlador->generateAllControlleres();


$generador = new MAIN_GENERATOR();
$generador->setPath($pathMains);
$generador->setPathController($pathControllers);
$generador->setTables($tables);
$generador->generateAllMains();

echo "CONTRUCCION FINALIZADA!";
