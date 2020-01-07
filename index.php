<?php

require_once('loader.php');

//$args = array(
//    'baseID' => 'appDhGtCj7RtfzZTO',
//    'table' => 'Ideas',
//    'ATKey' => 'keygWCs5sRSCFX2yQ'
//);
//
//$airtable  = new SorinMartaAirtable\Airtable($args);

$data['fields']['Name'] = 'Sorin';
$data['fields']['Value'] = 'Works';

$args = array(
    'ATKey' => 'keygWCs5sRSCFX2yQ',
    'ATBase' => 'appDhGtCj7RtfzZTO',
    'ATTable' => 'Testing',
    'ATEndpoint' => 'https://api.airtable.com/v0/',

    'requestType' => 'POST',
    'recordsLimit' => '3',
    'fieldData' => $data

);

$request = new \SorinMartaAirtable\Request($args);
//$request->showResponse = true;
$request->postNewRecords($args)->showResponse();