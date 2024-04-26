<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

$xmlFile = './../feeds_test/web-eau.net_1.xml';

$xmlFile = simplexml_load_file($xmlFile);

// affichage de l'en-tête
echo $xmlFile->title;
echo $xmlFile->link;
echo($xmlFile->owner ?? null);
echo $xmlFile->lastBuildDate;
echo count($xmlFile->item);
echo($xmlFile->category ?? null);
echo $xmlFile->description;
echo $xmlFile->language;
