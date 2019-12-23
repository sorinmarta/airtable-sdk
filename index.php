<?php

require_once('loader.php');

$args = array(
    'baseID' => 'test',
    'table' => 'test',
    'ATKey' => 'test'
);

$airtable  = new SorinMartaAirtable\Airtable($args);
$airtable->displayErrors();