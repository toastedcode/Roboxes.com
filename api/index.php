<?php

require_once '../common/database.php';
require_once '../common/registryEntry.php';
require_once '../common/time.php';
require_once 'rest.php';

$router = new Router();

$router->add("register", function($params) {
   if (isset($params["chipId"]))
   {
      $registryEntry = new RegistryEntry();
      $registryEntry->chipId = $params->get("chipId");
      $registryEntry->macAddress = $params->get("macAddress");
      $registryEntry->ipAddress = $params->get("ipAddress");
      $registryEntry->roboxName = $params->get("roboxName");
      $registryEntry->userId = $params->get("userId");
      $registryEntry->lastContact = Time::now("Y-m-d H:i:s");
      
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
});

$router->add("unregister", function($params) {
   
   if (isset($params["chipId"]))
   {
      $chipId = $params["chipId"];
      
      $database = new RoboxesDatabase();
      
      $database->connect();
      
      if ($database->isConnected())
      {
         if ($database->existsInRegistry($chipId))
         {
            $database->unregister($chipId);
         }
      }
   }
});

$router->route();
?>