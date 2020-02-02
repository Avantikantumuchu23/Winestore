<!DOCTYPE>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WineStore</title>
<style type="text/css">

table.db-table 		{ border-right:1px solid #ccc; border-bottom:1px solid #ccc; }
table.db-table th	{ background:#191614; padding:5px; border-left:1px solid #ccc; border-top:1px solid #ccc; }
table.db-table td	{ padding:5px; border-left:1px solid #ccc; border-top:1px solid #ccc; }
tr:nth-child(even){background-color: #33ff99}
tr:nth-child(odd){background-color: #f2f2f2}
th{color:#FFF;}
</style>
</head>
<body>
<?php
	include "connect.php";
	ini_set("SMTP", "smtp.gmail.com");
	ini_set("sendmail_from", "foodclubcs531@gmail.com");
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	$winename = $_GET["wine_name"];
	$winetype = $_GET["r1"];
	$year = $_GET["year"];
	$price = $_GET["price"];
	$wineryname = $_GET["winery_name"];
	$regionname = $_GET["regionName"];
	echo "<body style='background-position:center' bgcolor='#333333'>";
	echo "<p style='color:#99ff99;font-size:20px;'>Connected Successfully</p><br>";
	
	// Connect to the  server
	try{
    	if (!($connection = @ mysqli_connect($servername, $username, $password)))
    		throw new Exception('Could not connect to database:'.mysqli_error());
	}
	catch(Exception $e){
		echo 'Caught Exception: ',  $e->getMessage(), "\n";
	}
	// Generating a random wine id
	$wineID = rand(1050, 2000);
	$wineryID = rand(301,400);
	$custID = rand(655,1000);
	$orderID = rand(7,10);
	$itemID = rand(1,10);
	$onHand = rand(1,999);
	
   	//Code for date Display
   	
   	$date_array = getdate();
   	$formated_date  = "";
    $formated_date .= $date_array['year']. "-";
    $formated_date .= $date_array['mon'] . "-";
    $formated_date .= $date_array['mday'] ;
   
   	//print $formated_date;
	
	$wineWineTypeID = "SELECT wine_type_id FROM wine_type WHERE wine_type = '$winetype' ";
	$regionID = "SELECT region_id FROM region WHERE region_name = '$regionname'";
	
	//This is the execute code for wine ID from wine type
	$resultWineTypeID = $conn->query($wineWineTypeID);
	$mysqliDebug = true;
   try{
     		if (!$resultWineTypeID and $mysqliDebug)
       			throw new Exception('Query error:'.mysqli_error()."<br />".$resultWineTypeID);
	}
	 catch(Exception $e)
     	{
         	echo 'Caught Exception: ',  $e->getMessage(), "\n";
     	}
	if(!$resultWineTypeID){
		echo 'Wine ID - Could not run query';
		exit;
	}
	// code ends
	//This is the execute code to get region ID based on region name
	$resultRegionID = $conn->query($regionID);
	if(!$resultRegionID){
		echo 'RegionID - Could not run query';
		exit;
	}
	//code for region ID ends
	
	$winerow = mysqli_fetch_row($resultWineTypeID);
	$regionrow = mysqli_fetch_row($resultRegionID);
	//echo "$winerow[0]";
	$wine="INSERT INTO wine(wine_id, wine_name, wine_type, year, winery_id, description) VALUES ('$wineID', '$winename', '$winerow[0]', '$year', '$wineryID', NULL)";
	//echo "$wine";
	$winery = "INSERT INTO winery(winery_id, winery_name, region_id) VALUES ('$wineryID', '$wineryname', '$regionrow[0]') ";
	$items = "INSERT INTO items(cust_id,order_id, item_id, wine_id, qty, price) VALUES('$custID', '$orderID', '$itemID', '$wineID', '1', $price)";
	$inventory = "INSERT INTO inventory(wine_id, inventory_id, on_hand, cost, date_added) VALUES('$wineID', '1', '$onHand', '$price','$formated_date')";
	
	//This is the execute query code to insert in to wine table.
	$resultWine = $conn->query($wine);
	if(!$resultWine){
		echo 'Wine - Could not run query';
		//exit;
	}
	//code for wine insertion ends
	
	//This is the execute query code to insert winery details in to winery table
	$resultWinery = $conn->query($winery);
	if(!$resultWinery){
		echo 'Winery - Could not run query';
		//exit;
	}
	//code for winery insertion ends
	
	//This is the execute query code to insert details in to items table
	$resultItems = $conn->query($items);
	if(!$resultItems){
		echo 'Items - Could not run query';
		//exit;
	}
	$resultInventory = $conn->query($inventory);
	if(!$resultInventory){
		echo 'Inventory - Could not run query';
		exit;
	}
	
	$displayWine="SELECT * FROM wine WHERE wine_id = '$wineID'";
	$displayWinery = "SELECT * FROM winery WHERE winery_id = '$wineryID'";
	$displayInventory = "SELECT * FROM inventory WHERE wine_id = '$wineID'";
	$resultDisplayWine = $conn->query($displayWine);
	if(!$resultDisplayWine){
		echo 'DisplayWine - Could not run query';
		exit;
	}
	
	$resultDisplayWinery = $conn->query($displayWinery);
	if(!$resultDisplayWinery){
		echo 'DisplayWinery - Could not run query';
		exit;
	}
		
	$resultDisplayInventory = $conn->query($displayInventory);
	if(!$resultDisplayInventory){
		echo 'DisplayWine - Could not run query';
		exit;
	}
	
	// code that prints details of the wine table
	if ($resultDisplayWine->num_rows > 0)
     {
         // ... print out a header
         
			echo "<span style='font-family:Times New Roman, Times, serif;color:white;text-align:center;'> <p style='text-align:center;font-size:20px;'><b> These are the entries that you have entered in Wine table </b></p> </span><br>";
         	echo "<table class=table-db align='center' cellpadding='2' cellspacing='2' border:1px solid >";
			echo "<tr bgcolor='#FFF'>";
        	echo" <th bgcolor='#191614'>Wine ID</th>";
        	echo" <th bgcolor='#191614'>Wine Name</th>";
        	echo" <th bgcolor='#191614'>Wine Type</th>";
        	echo" <th bgcolor='#191614'>Year</th>";
        	echo" <th bgcolor='#191614'>Winery ID</th>";
         	echo "</tr>";
          		 $i=1;
 					while($row = mysqli_fetch_array($resultDisplayWine)) {
 						if($i%2==0)
 						{
     						echo '<tr bgcolor="#33ff99">';
 						}
 						else
 						{
    						echo '<tr bgcolor="#f2f2f2">';
 						}
    			 $i++;
		    	  echo "<td>" . $row['wine_id'] . "</td>";
		          echo "<td>" . $row['wine_name'] . "</td>";
			      echo "<td>" . $row['wine_type'] . "</td>";
			      echo "<td>" . $row['year'] . "</td>";
			      echo "<td>" . $row['winery_id'] . "</td>";
			      echo "</tr>";
                   }
	 	echo "</table>";
	 	echo "<br />\n";
	 	echo "<br />\n";
		echo "<br />\n";
	 }
	if ($resultDisplayWinery->num_rows > 0)
     {
         // ... print out a header
         
			echo "<span style='font-family:Times New Roman, Times, serif;color:white;'> <p style='text-align:center;font-size:20px;'><b>These are the entry that you have entered in Winery table </b></p> </span><br>";
          	echo "<table class=table-db align='center' cellpadding='2' cellspacing='2' border:1px solid >";
			echo "<br>";
			echo "<tr bgcolor='#FFF'>";
        	echo" <th bgcolor='#191614'>Winery ID</th>";
        	echo" <th bgcolor='#191614'>Winery Name</th>";
        	echo" <th bgcolor='#191614'>Region ID</th>";
        	echo "</tr>";
          		 $i=1;
 					while($row = mysqli_fetch_array($resultDisplayWinery)) {
 						if($i%2==0)
 						{
     						echo '<tr bgcolor="#1876B6">';
 						}
 						else
 						{
    						echo '<tr bgcolor="#66CDAA">';
 						}
    			 $i++;
		    	  echo "<td>" . $row['winery_id'] . "</td>";
		          echo "<td>" . $row['winery_name'] . "</td>";
			      echo "<td>" . $row['region_id'] . "</td>";
			      echo "</tr>";
					}
	 	echo "</table>";
	 	echo "<br />\n";
	 	echo "<br />\n";
		echo "<br />\n";
	 }
if ($resultDisplayInventory->num_rows > 0)
     {
         // ... print out a header
			echo "<span style='font-family:Times New Roman, Times, serif;color:white;'> <p style='text-align:center;font-size:20px;'><b> These are the entry that you have entered in to Inventory table </b></p> </span><br>";
          	//echo "<h3><p style='color:#EC983E;'>Wines belonging to: <b> $regionname </b> Type: <b> $winetype</b>  With Price between: <b>//$price1</b> and <b>$price2</b>  And In The Year: <b>$year</b> <br></p></h3>";
		 	//echo "<div id='tbl3'>";
			echo "<table class=table-db align='center' cellpadding='2' cellspacing='2' border:1px solid >";
			echo "<tr bgcolor='#FFF'>";
        	echo" <th bgcolor='#191614'>Wine ID</th>";
        	echo" <th bgcolor='#191614'>Inventory ID</th>";
        	echo" <th bgcolor='#191614'>On Hand</th>";
			echo" <th bgcolor='#191614'>Price</th>";
			echo" <th bgcolor='#191614'>Date Added</th>";
        	echo "</tr>";
          		 $i=1;
 					while($row = mysqli_fetch_array($resultDisplayInventory)) {
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
		          echo "<td>" . $row['inventory_id'] . "</td>";
			      echo "<td>" . $row['on_hand'] . "</td>";
			      echo "<td>" . $row['cost'] . "</td>";
				  echo "<td>" . $row['date_added'] . "</td>";
			      echo "</tr>";
					}
	 	echo "</table>";
	 	echo "<br />\n";
	 	echo "<br />\n";
	 	echo "<br />\n";
	}
 $resultDisplayWine = $conn->query($displayWine);
  try {
	    if(!$resultDisplayWine)
 throw new Exception("Unable to execute query...");
  }
  catch(Exception $e)
     {
         echo 'Caught Exception: ',  $e->getMessage(), "\n";
     }
	  $resultDisplayWinery = $conn->query($displayWinery);
  try {
	    if(!$resultDisplayWine)
 throw new Exception("Unable to execute query...");
  }
  catch(Exception $e)
     {
         echo 'Caught Exception: ',  $e->getMessage(), "\n";
     }
	  $resultDisplayInventory = $conn->query($displayInventory);
  try {
	    if(!$resultDisplayWine)
 throw new Exception("Unable to execute query...");
  }
  catch(Exception $e)
     {
         echo 'Caught Exception: ',  $e->getMessage(), "\n";
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
          	$subject = "Inserted New records to Wine Database";
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
			$headers .= "From: <foodclubcs531@gmail.com>\r\n";
            
            if ($resultDisplayWine->num_rows > 0)
            {
           		 $message = "<html><body>";
				 $message.="Hi,".$username."<br>";
				 $message.= "<h2><b><span style='font-family:Times New Roman, Times, serif;color:red;'> You have added new records to Winestore</span></b></h2><br>";
				 $message.= "<h3><b><u><center><span style='font-family:Times New Roman, Times, serif;color:blue;'> {$resultDisplayWine->num_rows} record is inserted into Wine table</span></center></u></b></h3><br>";
				 $message .= "<table border='1' align=center>
               	 <tr>
               	 	<th>Wine ID</th>
               		<th>Wine Name</th>
               		<th>Wine Type</th>
               		<th>Year</th>
               		<th>Winery ID</th>
                  </tr>";
				$i=1;
               while($row = mysqli_fetch_array($resultDisplayWine)) 
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
                 	$message .= "<td>" . $row["wine_type"] . "</td>";
                 	$message .= "<td>" . $row["year"] . "</td>";
                 	$message .= "<td>" . $row["winery_id"] . "</td>";
                    $message .= "</tr>";
                    }

	        		$message .= "</table><br>";
				
			if ($resultDisplayWinery->num_rows > 0)
            {	
				  $message .= "<br>";
				  $message .= "<br>";
				  $message.= "<h3><b><u><center><span style='font-family:Times New Roman, Times, serif;color:blue;'> {$resultDisplayWinery->num_rows} record is inserted into Winery table</span></center></u></b></h3><br>";
				 $message .= "<table border='1' align=center>
               	 <tr>
               	 	<th>Winery ID</th>
               		<th>Winery Name</th>
               		<th>Region ID</th>
               	  </tr>";
				$i=1;
               while($row1 = mysqli_fetch_array($resultDisplayWinery)) 
                   {
					   if($i%2==0)
 						{
     						echo  '<tr bgcolor="#1876B6">';
 						}
 						else
 						{
    						echo '<tr bgcolor="#33ff99">';
 						}
    			 $i++;
                    $message .= "<tr>";
                 	$message .= "<td>" . $row1["winery_id"] . "</td>";
                 	$message .= "<td>" . $row1["winery_name"] . "</td>";
                 	$message .= "<td>" . $row1["region_id"] . "</td>";
                    $message .= "</tr>";
                    }
	        $message .= "</table><br>";
			}
			
			if ($resultDisplayInventory->num_rows > 0)
            {
				$message .= "<br>";
				$message .= "<br>";
				$message.= "<h3><b><u><center><span style='font-family:Times New Roman, Times, serif;color:blue;'> {$resultDisplayInventory->num_rows} record is inserted into Inventory table</span></center></u></b></h3><br>";
				 $message .= "<table border='1' align=center>
               	 <tr>
               	 	<th>Wine ID</th>
               		<th>Inventory ID</th>
               		<th>On Hand</th>
					<th>Price</th>
					<th>Date Added</th>
               	  </tr>";
				$i=1;
               while($row2 = mysqli_fetch_array($resultDisplayInventory)) 
                   {
					   if($i%2==0)
 						{
     						echo  '<tr bgcolor="#1876B6">';
 						}
 						else
 						{
    						echo '<tr bgcolor="#33ff99">';
 						}
    			 $i++;
                    $message .= "<tr>";
                 	$message .= "<td>" . $row2["wine_id"] . "</td>";
                 	$message .= "<td>" . $row2["inventory_id"] . "</td>";
                 	$message .= "<td>" . $row2["on_hand"] . "</td>";
					$message .= "<td>" . $row2["cost"] . "</td>";
					$message .= "<td>" . $row2["date_added"] . "</td>";
                    $message .= "</tr>";
                    }
	        $message .= "</table><br>";
		} 
		$message .= "</body></html>";
				if (mail($to,$subject, $message, $headers)) {
						
                       		echo("<p style='color:#ffffff;font-size:20px;'>Search results mailed successfully to <b>$to</b></p>");
                      	} else {
                       		echo("<p>Message not delivered");
                    	}
		}
		else
		{
					$body = "Hi,".$username.". You have made no search results!!!";

                     	if (mail($to, $subject, $body,$headers)) {
                       		echo("<p style='color:#ffffff;font-size:15px;'>Message successfully sent!</p>");
                      	} else {
                       		echo("<p>Message delivery");
                    	}

		 }

$conn->close();
?>
