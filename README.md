# Airtable-SDK
## The unofficial PHP SDK for the Airtable API

This is a PHP client for the Airtable API that does basic CRUD operations.

## Documentation

### Getting started

To get started you need to include the "loader.php" file in your script.
    
    <?php
    
    required_once('loader.php');
    
#### Required Parameters

- base - should be your <a href="https://community.airtable.com/t/what-is-the-app-id-where-do-i-find-it/2984">Airtable base ID</a>

- table - should be the name of your table in Airtable

- key - should be your <a href="https://support.airtable.com/hc/en-us/articles/219046777-How-do-I-get-my-API-key-"> Airtable API key </a>

#### Class initialisation

    // Set your Airtable parameters
        $params = array(['
            'base' => 'The ID of your base',
            'table' => 'The name of your table',
            'key'   => 'Your Airtable Key'
        ']);
        
        // Create your Airtable object
        $airtable = new \SorinMartaAirtable\Airtable($params);

### Basic CRUD

#### Create

In order to create a new record in Airtable you have to pass your JSON data to the 'data' method and then to call the 'create' method.

Code sample:

    // Set your JSON Data
    $yourJSONData = // Your data
    
    //Pass your JSON data
    $airtable->data($yourJSONData);
    
    // Call the create method to send the request to Airtable
    $airtable->create();

#### Read

To read data from Airtable you need to call the 'get' method.

Code sample:

    $airtable->get();
    
##### Filter By Forumula

In order to set a formula to filter your content you have to pass it to the 'filter' method.

Code sample:

    $formula = // Your Airtable formula
    
    $airtable->filter($formula);
    $airtable->get();
    
##### MaxRecords

To set a limit to the records you are retrieving you have to pass your number to the 'maxRecords' method.

Code sample:

    $records = 3;
    
    $airtable->maxRecords($records);
    $airtable->get();

#### Update

*The update method works with only 1 record at a time. So if you are trying to update multiple records you will have to create a loop.*

To update a record inside your Airtable you have to call the 'update' method.

**Please note that the update method also searches for your record so make sure to pass a Filter By Formula to the object**

Code sample;

    $filter = // Your formula;
    $data = // Your data;
    
    $airtable->filter($filter);
    $airtable->data($data);
    $airtable->update();
    
#### Delete

*The delete method works with only 1 record at a time. So if you are trying to delete multiple records you will have to create a loop.*

To delete a record inside your Airtable you have to call the 'delete' method.

**Please note that the delete method also searches for your record so make sure to pass a Filter By Formula to the object**

Code sample;

    $filter = // Your formula;
    
    $airtable->filter($filter);
    $airtable->delete();