<?php

include "connect.php";

$wine_ID=$_GET["wine_ID"];
$winery_ID=$_GET["winery_ID"];
$wine_name=$_GET["wine_name"];
$winery_name=$_GET["winery_name"];
$price=$_GET["price"];
$result= 0;
echo "<body style='background-position:center' bgcolor='#333333'>";
 echo "<br>";
		
	try
     {
      if (!($connection = @ mysqli_connect($servername, $username, $password)))
      throw new Exception('Could not connect to database:'.mysqli_error());
     }
     catch(Exception $e)
     {
         echo "<span style=color:#FFF>'Caught Exception: '",  $e->getMessage(), "<br>";
     }
	 $wine = "SELECT wine_id from wine where wine_id=$wine_ID";
	  $wineresult = $conn->query($wine);
	  try
	  {
	  if(mysqli_num_rows($wineresult)==0) throw new Exception("Wine ID not found");
	  }
	  catch(Exception $e)
     {
         echo "<span style=color:#FFF>",  $e->getMessage(), "<br>";
     }
	  $winery = "SELECT winery_id from winery where winery_id=$winery_ID";
	  $wineryresult = $conn->query($winery);
	  try
	  {
	  if(mysqli_num_rows($wineryresult)==0) throw new Exception("Winery ID not found");
	  }
	  catch(Exception $e)
     {
         echo "<span style=color:#FFF>",  $e->getMessage(), "\n";
     }
	 
	  if(!mysqli_num_rows($wineresult)==0 and !mysqli_num_rows($wineryresult)==0)
	  {
	 $final1 = "SELECT wine.wine_id, wine_name, winery.winery_id, winery_name, cost
	           FROM inventory,wine,winery
			   where inventory.wine_id = wine.wine_id
			   AND inventory.wine_id=$wine_ID
			   AND winery.winery_id=$winery_ID";
	 $result1 = $conn->query($final1);
	 if($result1 ->num_rows>0)
	 {		
	 		echo "<h3><b><p style='color:#ffffff;text-align:center;'>Displaying Search Results of Wine Store Before Modification</b></h3></p>";
		 	echo "<div id='tbl'>";
			echo "<table align='center' cellpadding='2' cellspacing='2' >";
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
   echo"<span style=color:#FFF> 0 records found<br>";
     // Report how many rows were found

  } // end of function
	  }
	  if(!mysqli_num_rows($wineresult)==0 and !mysqli_num_rows($wineryresult)==0)
	  {
	 $query1 = "UPDATE inventory SET cost=\"$price\" where wine_id=\"$wine_ID\"";
	 $query2 = "UPDATE wine SET wine_name = \"$wine_name\" where wine_id=\"$wine_ID\"";
	 $query3 = "UPDATE winery SET winery_name = \"$winery_name\" where winery_id=\"$winery_ID\"";
	 $mysqliDebug = true;
	 $result2 = $conn->query($query1);
	 try {
	    if(!$result2)
 			throw new Exception("Wine ID not found, Please enter correct ID");
  	}
  catch(Exception $e)
     {
         echo "span style=color:#FFF>Caught Exception: ",  $e->getMessage(), "\n";
     }
	 $result3 = $conn->query($query2);
	 try {
	    if(!$result3)
 			throw new Exception("Wine ID not found, Please enter correct ID");
  	}
  catch(Exception $e)
     {
         echo "span style=color:#FFF>Caught Exception: ",  $e->getMessage(), "\n";
     }
	 $result4 = $conn->query($query3);
	 try {
	    if(!$result4)
 			throw new Exception("Winery ID not found, Please enter correct ID");
  	}
  catch(Exception $e)
     {
         echo "<span style=color:#FFF>Caught Exception: ",  $e->getMessage(), "\n";
     }
	 $final = "SELECT wine.wine_id, wine_name, winery.winery_id, winery_name, cost
	           FROM inventory,wine,winery
			   where inventory.wine_id = wine.wine_id
			   AND inventory.wine_id=$wine_ID
			   AND winery.winery_id=$winery_ID";
	 $result = $conn->query($final);
	 if($result ->num_rows>0)
	 {
		 echo "<br />\n";
	 echo "<br />\n";
	 echo "<br />\n";
	 echo "<span style=color:#FFF;align:center;font-size:20px;>After Update ";
		 	echo "<div id='tbl'>";
			echo "<table align='center' cellpadding='2' cellspacing='2' >";
			echo "<tr bgcolor='#FFF'>";
			echo" <th>Wine ID</th>";
			echo" <th>Wine Name</th>";
        	echo" <th>Winery ID</th>";
        	echo" <th>Winery Name</th>";
        	echo" <th>Price</th>";
			echo "</tr>";
          		 $i=1;
 					while($row = mysqli_fetch_array($result)) {
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
   echo"<span style=color:#FFF> 0 records found<br>";
     // Report how many rows were found

  } // end of function
	  
	  
  $result = $conn->query($final);
  try {
	    if(!$result)
 			throw new Exception("Unable to execute query...");
  	}
  catch(Exception $e)
     {
         echo "<span style=color:#FFF>Caught Exception: ",  $e->getMessage(), "\n";
     }
  
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
          	$subject = "Updated Wine Store Results";
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
			$headers .= "From: <foodclubcs531@gmail.com>\r\n";
            
            if ($result->num_rows > 0)
            {
           		 $message = "<html><body>";
				 $message.="Hi,".$username."<br>";
				 $message.= "<span style='font-family:Times New Roman, Times, serif;color:red;'> {$result->num_rows} Displaying Updated Records </span><br>";
				 $message.= "<h4><p style='color:#1876B6;'>Wines belonging to: <b>$wine_ID</b> Wine Name: <b>$wine_name</b>  With Winery ID <b>$winery_ID</b> and Winery Name <b>$winery_name</b>  having cost: <b>$price</b> <br></p></h4>";
              	 $message .= "<table border='1' align=center>
               	 <tr>
               	 	<th>Wine ID</th>
               		<th>Wine Name</th>
               		<th>Winery ID</th>
               		<th>Winery Name</th>
               		<th>Price</th>
               		
                </tr>";
				$i=1;
               while($row = mysqli_fetch_array($result)) 
                   {
					   if($i%2==0)
 						{
     						echo  '<tr bgcolor="#1876B6">';
 						}
 						else
 						{
    						echo '<tr bgcolor="#66CDAA">';
 						}
    			 $i++;
                    $message .= "<tr>";
                 	$message .= "<td>" . $row["wine_id"] . "</td>";
                 	$message .= "<td>" . $row["wine_name"] . "</td>";
                 	$message .= "<td>" . $row["winery_id"] . "</td>";
                 	$message .= "<td>" . $row["winery_name"] . "</td>";
                 	$message .= "<td>" . $row["cost"] . "</td>";
                    $message .= "</tr>";
                    }

	        $message .= "</table><br>";
			$message .= "</body></html>";
			
			if (mail($to,$subject, $message, $headers)) {
						
                       		echo("<p style='color:#ffffff;font-size:20px;'>Search results mailed successfully to <b>$to</b></p>");
                      	} else {
                       		echo("<p>Message delivery");
                    	}
		}
		else
		{
					$body = "Hi,".$username.". You made no updates!!!";

                     	if (mail($to, $subject, $body,$headers)) {
                       		echo("<p>Message successfully sent!</p>");
                      	} else {
                       		echo("<p>Message delivery");
                    	}

		 }
	  }
  $conn->close();
	 
	 
?>