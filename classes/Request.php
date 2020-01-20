<?php

namespace SorinMartaAirtable;

/**
 * Class Request
 * @package SorinMartaAirtable
 *
 * This class is responsible with all the requests that are going to be sent to the Airtable API and also with the error handling
 */

class Request
{
    // The Airtable credentials
    protected $ATKey;
    protected $ATBase;
    protected $ATEndpoint;
    protected $ATTable;

    // Constructor arguments
    protected $filterByFormula;
    protected $recordsLimit;
    protected $fieldArrayData;

    // Utilities
    protected $errors;
    private   $temporaryResponse;

    // Launches the element, validates the data and assigns the data to the class properties
    public function __construct($args)
    {
        $this->validateData($args);
        $this->dataAssign($args);
    }

    // Validates the data
    protected function validateData($args)
    {
        /**
         * Search by Formula
         */

        if (isset($args['filterByFormula'])){
            if(empty($args['filterByFormula'])){
                $this->addError('Filter by Formula value is empty');
            }
        }

        /**
         * Field Data
         */

        if (isset($args['fieldData'])){
            if (!is_array($args['fieldData'])){
                $this->addError('The data field must be an array');
            }
        }

        /**
         * Records limit
         */

        if(isset($args['recordsLimit'])) {
            if (!is_string($args['recordsLimit'])) {
                $this->addError('The records limit must be a string (between quotes)');
            }
        }

        /**
         * Airtable Key
         */

        if (!isset($args['ATKey'])){
            $this->addError('You need to configure the Airtable Key');
        }

        /**
         * Airtable Base
         */

        if (!isset($args['ATBase'])){
            $this->addError('You need to configure the Airtable Base');
        }

        /**
         * Airtable Table
         */

        if (!isset($args['ATTable'])){
            $this->addError('You need to configure the Airtable Table');
        }

        /**
         * Airtable Endpoint
         */

        if (!isset($args['ATEndpoint'])){
            $this->addError('You need to configure the Airtable Endpoint');
        }

        return true;
    }

    // Assigns the data to the class properties
    private function dataAssign($args)
    {
        $this->ATKey        =   $args['ATKey'];
        $this->ATBase       =   $args['ATBase'];
        $this->ATTable      =   $args['ATTable'];
        $this->ATEndpoint   =   $args['ATEndpoint'];

        if (isset($args['fieldData'])) {
            $this->fieldArrayData   =   $args['fieldData'];
        }

        if (isset($args['filterByFormula'])) {
            $this->filterByFormula  =   $args['filterByFormula'];
        }

        if (isset($args['recordsLimit'])) {
            $this->recordsLimit     =   $args['recordsLimit'];
        }

        return true;
    }

    // The link termination for the request arguments if they are set
    private function complementaryURL()
    {
        $link = null;

        if (isset($this->recordsLimit) || isset($this->filterByFormula)){
            $link = (isset($this->filterByFormula) || isset($this->recordsLimit) ? '/?' : false) . (isset($this->recordsLimit) ? '?maxRecords='.$this->recordsLimit : false) . (isset($this->filterByFormula) && isset($this->recordsLimit) ? '&' : false) . (isset($this->filterByFormula) ? 'filterByFormula=' . $this->filterByFormula:false);
        }

        return $link;
    }

    // Posts new records to Airtable
    protected function postNewRecords()
    {
        $con = curl_init();

        curl_setopt_array($con, array(
            CURLOPT_URL => $this->ATEndpoint . $this->ATBase . '/' . $this->ATTable . '/',
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer $this->ATKey",
                "Content-Type: application/json"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($this->fieldArrayData)
        ));

        $this->temporaryResponse = curl_exec($con);

        if (curl_error($con)){
            $this->addError(curl_error($con));
        }

        curl_close($con);

        return $this;
    }

    // Dumps the response array
    public function showResponse()
    {
        $response = $this->temporaryResponse;
        var_dump($response);
        $this->temporaryResponse = null;
    }

    // Returns the response array
    public function returnResponse()
    {
        return $this->temporaryResponse;
    }

    // Retrieves records
    protected function getRecords()
    {
        $con = curl_init();

        curl_setopt_array($con, array(
            CURLOPT_URL => $this->ATEndpoint . $this->ATBase . '/' . $this->ATTable . $this->complementaryURL(),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer $this->ATKey",
            ),
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_RETURNTRANSFER => true
        ));

        $this->temporaryResponse = curl_exec($con);

        if (curl_error($con)){
            $this->addError(curl_error($con));
        }

        curl_close($con);

        return $this;
    }

    // Updates records
    protected function updateRecord()
    {
        $con = curl_init();
        $jsonData = json_encode($this->fieldArrayData);
        $entry = json_decode($this->temporaryResponse);
        $entryID = $entry->records[0]->id;

        curl_setopt_array($con, array(
            CURLOPT_URL => $this->ATEndpoint . $this->ATBase . '/' . $this->ATTable . '/' . $entryID,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer $this->ATKey",
                "Content-Type: application/json"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => $jsonData
        ));

        $this->temporaryResponse = curl_exec($con);

        if (curl_error($con)){
            $this->addError(curl_error($con));
        }

        curl_close($con);

        return $this;
    }

    // Deletes records
    protected function deleteRecord()
    {
        $con = curl_init();
        $jsonData = json_encode($this->fieldArrayData);
        $entry = json_decode($this->temporaryResponse);
        $entryID = $entry->records[0]->id;

        curl_setopt_array($con, array(
            CURLOPT_URL => $this->ATEndpoint . $this->ATBase . '/' . $this->ATTable . '/' . $entryID,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer $this->ATKey",
                "Content-Type: application/json"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE'
        ));

        $this->temporaryResponse = curl_exec($con);

        if (curl_error($con)){
            $this->addError(curl_error($con));
        }

        curl_close($con);

        return $this;
    }

    // Adds an error to the errors property
    protected function addError($error)
    {
        array_push($this->errors,$error);
    }


    // Checks if there is any error
    private function errorsExist()
    {
        if (!empty($this->errors)) {
            return true;
        }else{
            return false;
        }
    }

    // If errors exist displays them and kills the script
    public function displayErrors()
    {
        if ($this->errorsExist()) {
            $errors = $this->errors;

            foreach ($errors as $error) {
                echo $error;
            }

            $this->clearErrors();
            die();
        }
    }

    // Clears the errors
    private function clearErrors()
    {
        unset($this->errors);
        $this->errors = array();
    }
}