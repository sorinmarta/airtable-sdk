<?php


namespace SorinMartaAirtable;


class Airtable
{
    private $airtableAPI = 'https://api.airtable.com/v0/';
    private $ATKey;
    private $table;
    private $base;
    private $errors;

    public function __construct($args)
    {
        $this->errors = array();

        if ($this->Arguments($args)){
            $this->setArguments($args);
            $this->testConnection();
        }

        $this->displayErrors();
    }

    private function setArguments($args)
    {
        $this->table = $args['table'];
        $this->base = $args['baseID'];
        $this->ATKey = $args['ATKey'];
    }

    private function Arguments($args)
    {
        if (!is_array($args)){
            $error = 'The airtable class arguments provided must be an array!';
            $this->addError($error);
        }

        if (!isset($args['baseID'])){
            $error = 'The airtable class requires an Airtable base ID';
            $this->addError($error);
        }

        if (!isset($args['table'])){
            $error = 'The airtable class requires a table name';
            $this->addError($error);
        }

        if (!isset($args['ATKey'])){
            $error = 'The airtable key is required';
            $this->addError($error);
        }

        return true;
    }

    private function testConnection()
    {
        $request = '/'.$this->table;
        $con = curl_init();
        curl_setopt_array($con, array(
            CURLOPT_URL => $this->airtableAPI . $this->base . $request,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer $this->ATKey",
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

    protected function addError($error)
    {
        array_push($this->errors,$error);
    }

    private function errorsExist()
    {
        if (!empty($this->errors)) {
            return true;
        }else{
            return false;
        }
    }

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

    private function clearErrors()
    {
        unset($this->errors);
        $this->errors = array();
    }
}