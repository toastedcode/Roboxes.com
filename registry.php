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
         $dateTime = new DateTime($registryEntry->lastContact);
         $dateTimeString = $dateTime->format("F j, g:i:s a");
         
         // Determine the interval between the supplied date and the current time.
         $interval = $dateTime->diff(new DateTime());
         
         // Convert to seconds.
         $seconds = ($interval->days * 24 * 60 * 60) + 
                    ($interval->h * 60 * 60) + 
                    ($interval->i * 60) + 
                    $interval->s;
         
         // Determine if board is online.
         $isOnline = ($seconds <= 30);
         $online = $isOnline? "online" : "offline";
         $disabled = $isOnline ? "" : "disabled";
         $label = $isOnline ? "Control" : "Offline";

         echo
<<<HEREDOC
         <tr>
            <td>$registryEntry->chipId</td>
            <td>$registryEntry->macAddress</td>
            <td>$registryEntry->ipAddress</td>
            <td>$registryEntry->roboxName</td>
            <td>$registryEntry->userId</td>
            <td>$dateTimeString</td>
            <td><button class="$online" onclick="location.href='control.php?chipId=$registryEntry->chipId';" $disabled>$label</button></td>
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
   <style>
      .online {
         background-color: #4CAF50;
         color: white;
      }
   </style>
</head>

<body>
   <?php $registryTable->render("")?>
</body>

</html>