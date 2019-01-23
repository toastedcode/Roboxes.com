<?php

require_once 'databaseKey.php';
require_once 'time.php';

interface Database
{
   public function connect();

   public function disconnect();

   public function isConnected();

   public function query(
      $query);
}

class MySqlDatabase implements Database
{
   function __construct(
      $server,
      $user,
      $password,
      $database)
   {
      $this->server = $server;
      $this->user = $user;
      $this->password = $password;
      $this->database = $database;
   }

   public function connect()
   {
      // Create connection
      $this->connection = new mysqli($this->server, $this->user, $this->password, $this->database);

      // Check connection
      if ($this->connection->connect_error)
      {
         // TODO?
      }
      else
      {
         $this->isConnected = true;
      }
   }

   public function disconnect()
   {
      if ($this->isConnected())
      {
         $this->connection->close();
      }
   }

   public function isConnected()
   {
      return ($this->isConnected);
   }

   public function query(
      $query)
   {
      $result = NULL;

      if ($this->isConnected())
      {
         $result = $this->connection->query($query);
      }

      return ($result);
   }
   
   protected function getConnection()
   {
      return ($this->connection);
   }

   private $server = "";

   private $user = "";

   private $password = "";

   private $database = "";

   private $connection;

   private $isConnected = false;
}

class RoboxesDatabase extends MySqlDatabase
{
   public function __construct()
   {
      global $SERVER, $USER, $PASSWORD, $DATABASE;
      
      parent::__construct($SERVER, $USER, $PASSWORD, $DATABASE);
   }
   
   public function getRegistryEntry($chipId)
   {
      $query = "SELECT * from registry WHERE chipId = \"$chipId\";";
      
      $result = $this->query($query);
      
      return ($result);
   }
   
   public function getRegistryEntries($userId)
   {
      $userClause = empty($userId) ? "" : "WHERE userId = \"$userId\"";
      $query = "SELECT * from registry $userClause ORDER BY chipId DESC;";
      
      $result = $this->query($query);
      
      return ($result);
   }
   
   public function existsInRegistry($chipId)
   {
      $query = "SELECT chipId from registry WHERE chipId = \"$chipId\";";

      $result = $this->query($query);
      
      return ($result && ($result->num_rows > 0));
   }
   
   public function register($registryEntry)
   {
      $lastContact = Time::toMySqlDate($registryEntry->lastContact);
      
      $query = 
         "INSERT INTO registry (chipId, macAddress, ipAddress, roboxName, userId, lastContact) " . 
         "VALUES ('$registryEntry->chipId', '$registryEntry->macAddress', '$registryEntry->ipAddress', '$registryEntry->roboxName', '$registryEntry->userId', '$lastContact');";
      echo $query;
      $this->query($query);
   }  
   
   public function updateRegistry($registryEntry)
   {
      $lastContact = Time::toMySqlDate($registryEntry->lastContact);
      
      $query =
         "UPDATE registry " .
         "SET macAddress = \"$registryEntry->macAddress\", ipAddress = \"$registryEntry->ipAddress\", roboxName = \"$registryEntry->roboxName\", userId = \"$registryEntry->userId\", lastContact = \"$lastContact\" " .
         "WHERE chipId = $registryEntry->chipId;";
      echo $query;
      $this->query($query);
   }
   
   public function unregister($chipId)
   {
      $query = "DELETE FROM registry WHERE chipId = $chipId;";
      
      $this->query($query);
   }
}

?>