<?php

require_once '../common/database.php';
require_once '../common/registryEntry.php';
require_once '../common/time.php';

function getParam($paramName)
{
   $value = "";
   
   if (isset($_POST[$paramName]))
   {
      $value= $_POST[$paramName];
   }
   else if (isset($_GET[$paramName]))
   {
      $value= $_GET[$paramName];
   }
   
   return ($value);
}

$registryEntry = new RegistryEntry();
$registryEntry->chipId = getParam("chipId");
$registryEntry->macAddress = getParam("macAddress");
$registryEntry->ipAddress = getParam("ipAddress");
$registryEntry->roboxName = getParam("roboxName");
$registryEntry->userId = getParam("userId");
$registryEntry->lastContact = Time::now("Y-m-d H:i:s");

if (!empty($registryEntry->chipId))
{
   $database = new RoboxesDatabase();
   
   $database->connect();
   
   if ($database->isConnected())
   {
      if ($database->existsInRegistry($registryEntry->chipId))
      {
         $database->updateRegistry($registryEntry);
      }
      else
      {
         $database->register($registryEntry);
      }
   }
}
?>