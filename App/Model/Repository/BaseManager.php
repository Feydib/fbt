<?php
namespace App\Model\Repository;

class BaseManager {

	protected $connection;

	protected $database;

	/**
	* collection name
	* @string
	*/
	protected $collection;

	/**
	* @var MongoCollection
	*/
	protected $_collection;


	function __construct($connection, $database){
		$this->connection = $connection;
		$this->database = $database;
		$this->_collection = $this->getCollection();
	}


  function getDb() {
    $db = $this->connection->selectDB($this->database);
    return $db;
  }

  function getCollection() {
    $db = $this->getDb();
    $collection = $db->selectCollection($this->collection);
    return $collection;
  }
 
}