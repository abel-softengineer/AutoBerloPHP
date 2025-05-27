<?php
require_once 'storage.php';


    $jsonIO = new JsonIO('cars.json');
    $carStorage = new Storage($jsonIO); 
    $cars = $carStorage->findAll(); 

?>