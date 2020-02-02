 
 <?php

include "connect.php";
ini_set("SMTP", "smtp.gmail.com");
ini_set("sendmail_from", "foodclubcs531@gmail.com");
error_reporting(E_ALL);
ini_set('display_errors', '1');
// Secure the user parameter $regionName
  $wine_id = $_GET["wine_ID"];
  $winery_id = $_GET["winery_ID"];
  
/*  echo $regionName;
 echo $wine_id;
 echo $winery_id; */
 echo "<body style='background-position:center' bgcolor='#333333'>";
 echo "<br>";
	
	 // Connect to the  server
  try
     {
      if (!($connection = @ mysqli_connect($servername, $username, $password)))
      throw new Exception('Could not connect to database:'.mysqli_error());
     }
     catch(Exception $e)
     {
         echo 'Caught Exception: ',  $e->getMessage(), "\n";
     }

	$wine="SELECT wine_id from wine where wine_id=$wine_id";
	$wineresult = $conn->query($wine);
	  try
	  {
	  if(mysqli_num_rows($wineresult)==0) throw new Exception("Wine ID not found");
	  }
	  catch(Exception $e)
     {
         echo "<span style=color:#FFF>Caught Exception: ",  $e->getMessage(), "<br>";
     }
	$winery="SELECT winery_id from wine where winery_id=$winery_id";
	$wineryresult = $conn->query($winery);
	  try
	  {
	  if(mysqli_num_rows($wineryresult)==0) throw new Exception("Winery ID not found");
	  }
	  catch(Exception $e)
     {
         echo "<span style=color:#FFF>Caught Exception: ",  $e->getMessage(), "\n";
     }
	 
	 
	 if(!mysqli_num_rows($wineresult)==0 and !mysqli_num_rows($wineryresult)==0)
	  {
	 $final1 = "SELECT wine.wine_id, wine_name, winery.winery_id, winery_name, cost
	           FROM inventory,wine,winery
			   where inventory.wine_id = wine.wine_id
			   AND inventory.wine_id=$wine_id
			   AND winery.winery_id=$winery_id";
	 $result1 = $conn->query($final1);
	 $mysqliDebug = true;
  	 try{
     		if (!$result1 and $mysqliDebug)
       			throw new Exception('Query error:'.mysqli_error()."<br />".$final1);
	}
	 catch(Exception $e)
     	{
         	echo 'Caught Exception: ',  $e->getMessage(), "\n";
     	}
	 if($result1 ->num_rows>0)
	 {		
	 		echo "<span style=color:#FFF;align:center;font-size:20px;>Before Delete";
		 	echo "<div id='tbl'>";
			echo "<table align='left' cellpadding='2' cellspacing='2' >";
			echo "<tr bgcolor='#FFF'>";
			echo" <th>Wine ID</th>";
			echo" <th>Wine Name</th>";
        	echo" <th>Winery ID</th>";
        	echo" <th>Winery Name</th>";
        	echo" <th>Price</th>";
			echo "</tr>";
          		 $i=1;
 					while($row = mysqli_fetch_array($result1)) {
 						if($i%2==0)
 						{
     						echo '<tr bgcolor="#1876B6">';
 						}
 						else
 						{
    						echo '<tr bgcolor="#66CDAA">';
 						}
    			 		$i++;
						echo "<td>" . $row['wine_id'] . "</td>";
						echo "<td>" . $row['wine_name'] . "</td>";
          				echo "<td>" . $row['winery_id'] . "</td>";
	      				echo "<td>" . $row['winery_name'] . "</td>";
	      				echo "<td>" . $row['cost'] . "</td>";
          echo "</tr>";
					}
	 echo "</table>";
 echo "</div>";
	 
  echo "</body>";
 
 
     } // end if $rowsFound body
   else {
   print" 0 records found<br>";
     // Report how many rows were found

  } // end of function
	  }
	  
	  if(!mysqli_num_rows($wineresult)==0 and !mysqli_num_rows($wineryresult)==0)
	  {
 		$winequery = "DELETE from wine Where wine_id= \"{$wine_id}\";";
		$wineryquery= "DELETE from winery Where winery_id= {$winery_id} ";
		$inventoryquery = "DELETE from inventory Where wine_id= {$wine_id} ";
		$result1 = $conn->query($winequery);
	    $mysqliDebug = true;
   try{
     		if (!$result1 and $mysqliDebug)
       			throw new Exception('Query error:'.mysqli_error()."<br />".$winequery);
	}
	 catch(Exception $e)
     	{
         	echo 'Caught Exception: ',  $e->getMessage(), "\n";
     	}
		$result2 = $conn->query($wineryquery);
	try{
     		if (!$result2 and $mysqliDebug)
       			throw new Exception('Query error:'.mysqli_error()."<br />".$wineryquery);
	}
	 catch(Exception $e)
     	{
         	echo 'Caught Exception: ',  $e->getMessage(), "\n";
     	}
		$result3 = $conn->query($inventoryquery);
	try{
     		if (!$result3 and $mysqliDebug)
       			throw new Exception('Query error:'.mysqli_error()."<br />".$inventoryquery);
	}
	 catch(Exception $e)
     	{
         	echo 'Caught Exception: ',  $e->getMessage(), "\n";
		}
echo "<br />\n";
echo "<br />\n";
echo "<br />\n";		
echo "<br />\n";
echo "<h4><b><p style='color:ffffff;'>Record with Wine ID <b>$wine_id</b> and Winery ID <b>$winery_id</b> is deleted</b></h4></p>";
echo '<br>';

//mail
       //print "Test send PHP email using PHP's internal mail() function\n<br>";
      $file = fopen("http://cs99.bradley.edu/~akantumuchu/Assignment2/cgi-bin/singlelogin.txt", "r");
      if (!feof($file)) {

      		$line = fgets($file);
      		$array = explode('#', $line);
      		$username=$array[0];
      		$email=$array[1];
      }
      fclose($file);
          	$to = $email;
			$subject = "Record Deleted from Winestore";
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
			$headers .= "From: <foodclubcs531@gmail.com>\r\n";
            $message = "<html><body>";
		    $message.="Hi,".$username."<br>";
			$message.= "<h4><b><p style='color:red;'>Record with Wine ID <b>$wine_id</b> and Winery ID <b>$winery_id</b> is deleted</b></h4></p>";
				
			$message.= "</body></html>";
			
			if (mail($to,$subject, $message, $headers)) 
			{
						 echo("<p style='color:#ffffff;'>Query changes are mailed successfully to <b>$to</b></p>");
            } 
			else 
			{
                       	//$body = "Hi,".$username.". You have made no search results!!!";
                     	if (mail($to, $subject, $body,$headers)) {
                       		echo("<p>Message successfully sent!</p>");
                      	} else {
                       		echo("<p>Message delivery");
                    	}
             }
	  }
$conn->close();
?>
