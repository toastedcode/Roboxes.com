<?php

require 'common/database.php';
require 'common/registryEntry.php';

class RegistryTable
{
   public function getHtml($userId)
   {
      $registryEntries = RegistryTable::getRegistryEntries($userId);
      
      echo
<<<HEREDOC
      <table>
         <tr>
            <th>Chip ID</th>
            <th>MAC address</th>
            <th>LAN IP address</th>
            <th>Robox name</th>
            <th>User ID</th>
            <th>Last contact</th>
            <th></th>
            <th></th>
         </tr>
HEREDOC;
       
      foreach ($registryEntries as $registryEntry)
      {
         echo
<<<HEREDOC
         <tr>
            <td>$registryEntry->chipId</td>
            <td>$registryEntry->macAddress</td>
            <td>$registryEntry->ipAddress</td>
            <td>$registryEntry->roboxName</td>
            <td>$registryEntry->userId</td>
            <td>$registryEntry->lastContact</td>
            <td><button onclick="location.href='control.php?chipId=$registryEntry->chipId';">Control</button></td>
         </tr>
HEREDOC;
      }
         
      echo "</table>";
   }
   
   public function render($userId)
   {
      echo ($this->getHtml($userId));
   }
   
   private function getRegistryEntries($userId)
   {
      $registryEntries = new ArrayObject();
      
      $database = new RoboxesDatabase();
      
      $database->connect();
      
      if ($database->isConnected())
      {
         $result = $database->getRegistryEntries($userId);
         
         while ($result && ($row = $result->fetch_assoc()))
         {
            $registryEntry = RegistryEntry::load($row["chipId"]);
            
            if ($registryEntry)
            {
               $registryEntries[] = $registryEntry;
            }
         }
      }

      return ($registryEntries);
   }
}

$registryTable = new RegistryTable();
?>

<html>

<head>
   <script>
      function control(chipId)
      {
         alert(chipId);
      }
   </script>
</head>

<body>
   <?php $registryTable->render("")?>
</body>

</html>