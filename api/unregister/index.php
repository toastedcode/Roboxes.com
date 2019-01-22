<?php

require_once '../../common/database.php';

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

$chipId = getParam("chipId");

if (!empty($chipId))
{
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

?>