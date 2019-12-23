<?php


namespace SorinMartaAirtable;


class Airtable
{
    private $airtableAPI = 'https://api.airtable.com/v0/';
    private $airtableKey;
    private $table;
    private $base;
    private $errors;

    public function __construct($args)
    {
        $this->errors = array();

        if ($this->Arguments($args)){
            $this->setArguments($args);
        }
    }

    private function setArguments($args)
    {
        $this->table = $args['table'];
        $this->base = $args['baseID'];
        $this->airtableKey = $args['ATKey'];
    }

    private function addError($error)
    {
        array_push($this->errors,$error);
    }

    public function displayErrors()
    {
        if (!empty($this->errors)) {
            $errors = $this->errors;

            foreach ($errors as $error) {
                echo $error;
            }

            $this->clearErrors();
        }
    }

    private function clearErrors()
    {
        unset($this->errors);
        $this->errors = array();
    }

    private function Arguments($args)
    {
        if (!is_array($args)){
            $error = 'The airtable class arguments provided must be an array!';
            $this->addError($error);

            return false;
        }

        if (!isset($args['baseID'])){
            $error = 'The airtable class requires an Airtable base ID';
            $this->addError($error);

            return false;
        }

        if (!isset($args['table'])){
            $error = 'The airtable class requires a table name';
            $this->addError($error);

            return false;
        }

        if (!isset($args['ATKey'])){
            $error = 'The airtable key is required';
            $this->addError($error);

            return false;
        }
    }
}