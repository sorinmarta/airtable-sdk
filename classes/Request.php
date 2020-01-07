<?php

//TODO

// Figure what data is needed from the user

// * Request type [!]
// * Search by formula value [!]
// * Field Data Array [!]
// * Records limit [!]

// * AT Key [!]
// * AT Base [!]
// * AT Table [!]
// * AT Endpoint [!]

// Create the data validator[!]
// Create the constructor that verifies the passed data and launches the required function depending on what kind of request is set
// Create the first request methods
// Refactor


namespace SorinMartaAirtable;


class Request extends Airtable
{
    private $temporaryResponse;

    public function __construct($args)
    {
        $this->showResponse = false;

        $this->validateData($args);
    }

    private function validateData($args)
    {
        /**
         * Request Type
         */

        if (!isset($args['requestType'])){
            die('Post Type is not set');
        }

        $is_request_type = false;

        if($args['requestType'] == 'POST'){
            $is_request_type = true;
        }

        if($args['requestType'] == 'PUT'){
            $is_request_type = true;
        }

        if($args['requestType'] == 'PATCH'){
            $is_request_type = true;
        }

        if($args['requestType'] == 'GET'){
            $is_request_type = true;
        }

        if($args['requestType'] == 'DELETE'){
            $is_request_type = true;
        }

        if (!$is_request_type){
            die('The post type is not correct... ');
        }

        /**
         * Search by Formula
         */

        if (isset($args['searchByFormula'])){
            if(empty($args['searchByFormula'])){
                die('Search by Formula value is empty');
            }
        }

        /**
         * Field Data
         */

//        if (isset($args['fieldData'])){
//            if (is_array($args['fieldData'])){
//                die('The data field must be an array');
//            }
//        }

        /**
         * Records limit
         */

        if (!isset($args['recordsLimit'])){
            die('Records limit is not set');
        }

        if (!is_string($args['recordsLimit'])){
            die('The records limit must be a string (between quotes)');
        }

        /**
         * Airtable Key
         */

        if (!isset($args['ATKey'])){
            die('You need to configure the Airtable Key');
        }

        /**
         * Airtable Base
         */

        if (!isset($args['ATBase'])){
            die('You need to configure the Airtable Base');
        }

        /**
         * Airtable Table
         */

        if (!isset($args['ATTable'])){
            die('You need to configure the Airtable Table');
        }

        /**
         * Airtable Endpoint
         */

        if (!isset($args['ATEndpoint'])){
            die('You need to configure the Airtable Endpoint');
        }
    }

    public function postNewRecords($args)
    {
        $key = $args['ATKey'];

        $con = curl_init();
        curl_setopt_array($con, array(
            CURLOPT_URL => $args['ATEndpoint'] . $args['ATBase'] . '/' . $args['ATTable'] . '/',
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer $key",
                "Content-Type: application/json"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($args['fieldData'])
        ));

        $this->temporaryResponse = curl_exec($con);

        if (curl_error($con)){
            $this->addError(curl_error($con));
        }

        return $this;
    }

    public function showResponse(){
        $response = $this->temporaryResponse;
        var_dump($response);
    }
}

// DO THE FIELD DATA VALIDATION
// FIGURE OUT WHY THE ARRAY VALIDATION DOESN'T WORK