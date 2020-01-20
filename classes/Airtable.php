<?php

namespace SorinMartaAirtable;

/**
 * Class Airtable
 * @package SorinMartaAirtable
 *
 * The main class of the client. This is handling all the public functionality
 */


class Airtable extends Request
{
    // API required credentials
    private $airtableAPI = 'https://api.airtable.com/v0/';
    private $key;
    private $table;
    private $base;

    //Link variables
    private $filter;
    private $data;
    private $maxRecords;

    // Constructor that's verifying the user input and testing the Airtable connection
    public function __construct($args)
    {
        $this->errors = array();

        if ($this->Arguments($args)){
            $this->setArguments($args);
            $this->testConnection();
        }

        $this->displayErrors();
    }

    // Sets the arguments for the Airtable connection
    private function setArguments($args)
    {
        $this->table    =   $args['table'];
        $this->base     =   $args['base'];
        $this->key      =   $args['key'];
    }

    // Validates the arguments
    private function Arguments($args)
    {
        if (!is_array($args)){
            $error = 'The airtable class arguments provided must be an array!';
            $this->addError($error);
        }

        if (!isset($args['base'])){
            $error = 'The airtable class requires an Airtable base ID';
            $this->addError($error);
        }

        if (!isset($args['table'])){
            $error = 'The airtable class requires a table name';
            $this->addError($error);
        }

        if (!isset($args['key'])){
            $error = 'The airtable key is required';
            $this->addError($error);
        }

        return true;
    }
    // Tests the connection
    private function testConnection()
    {
        $request = '/'.$this->table;
        $con = curl_init();
        curl_setopt_array($con, array(
            CURLOPT_URL => $this->airtableAPI . $this->base . $request,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer $this->key",
            ),
            CURLOPT_RETURNTRANSFER => true
        ));

        $exec = curl_exec($con);
        $error = curl_error($con);
        curl_close($con);

        if ($error) {
            $this->addError('cURL error:' . $error);
        }

        $response = json_decode($exec, true);

        if (isset($response['error'])){
            $this->addError('Airtable Error: ' . $response['error']);
        }
    }

    // The argument setter for the Request object
    private function setRequestArguments()
    {
        $args = array(
            'ATKey'             =>  $this->key,
            'ATTable'           =>  $this->table,
            'ATBase'            =>  $this->base,
            'ATEndpoint'        =>  $this->airtableAPI,
        );

        if (!empty($this->filter)){
            $args['filterByFormula'] = $this->filter;
        }

        if (!empty($this->data)){
            array_push($args,'fieldData',$this->data);
            $args['fieldData'] = $this->data;
        }

        if (!empty($this->maxRecords)){
            $args['recordsLimit'] = $this->maxRecords;
        }

        return $args;
    }

    // Sets the filterByForumula argument
    public function filter($filterByFormula)
    {
        $this->filter = $filterByFormula;

        return $this;
    }

    // Sets the data argument
    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    // Sets the maxRecords argument
    public function maxRecords($maxRecords)
    {
        $this->maxRecords = $maxRecords;

        return $this;
    }

    // The function that adds new records to Airtable
    public function create()
    {
        $args = $this->setRequestArguments();

        $request = new Request($args);
        return $request->postNewRecords()->returnResponse();
    }

    // The function that retrieves data from Airtable
    public function get()
    {
        $args = $this->setRequestArguments();

        $request = new Request($args);
        return $request->getRecords()->returnResponse();
    }

    // Updates records in Airtable
    public function update()
    {
        $args = $this->setRequestArguments();

        $request = new Request($args);
        return $request->getRecords()->updateRecord()->returnResponse();
    }

    // Deletes records in Airtable
    public function delete()
    {
        $args = $this->setRequestArguments();

        $request = new Request($args);
        return $request->getRecords()->deleteRecord()->returnResponse();
    }
}