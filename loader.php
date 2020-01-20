<?php


namespace SorinMartaAirtable;

/**
 * Class loader
 * @package SorinMartaAirtable
 *
 * This class is responsible to import all the required files
 */


class loader
{
    // This variable stores the basic location of the classes
    private $classLocation;

    // The constructor that launches all the functions
    public function __construct()
    {
        $this->requireRequest();
        $this->requireAirtable();
    }

    // The function that takes care of defining the basic location of the classes
    private function getClassDirectory()
    {
        return $this->classLocation = __DIR__.'/classes/';
    }

    // The function that imports the Airtable class
    private function requireAirtable()
    {
        require_once($this->getClassDirectory().'Airtable.php');
    }

    // The function that imports the Request class
    private function requireRequest()
    {
        require_once($this->getClassDirectory().'Request.php');
    }
}

// Initial class creation
new loader();