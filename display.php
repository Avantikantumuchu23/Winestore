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
tr:nth-child(odd){background-color: #ffb3b3}
th{color:#FFF;}
</style>
</head>
<body style='background-position:center' bgcolor='#333333'>
<?php

include "connect.php";
ini_set("SMTP", "smtp.gmail.com");
ini_set("sendmail_from", "foodclubcs531@gmail.com");
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Show all wines in a region in a <table>
  function displayWinesList($connection,$query,$regionName,$wineType,$wineName)
                            
  {
     // Run the query on the server
     try{
     		if (!($result = @ mysql_query ($query, $connection)))
       			throw new Exception('Query error:'.mysql_error());
	}
	 catch(Exception $e)
     	{
         	echo 'Caught Exception: ',  $e->getMessage(), "\n";
     	}

     // Find out how many rows are available
     $rowsFound = @ mysql_num_rows($result);

     // If the query has results ...
     if ($rowsFound > 0)
     {
         // ... print out a header
         echo "<span style='font-family:Times New Roman, Times, serif;color:#ffffff;font-size:20px;text-align:center;'> Wines of $regionName region name and $wineType winetype</span><br>";
		 echo "<span style='font-family:Times New Roman, Times, serif;color:#ffffff;font-size:20px;text-align:center;'> {$rowsFound} records found matching your criteria</span><br>";
         // and start a <table  border width=50%>.
         echo "\n<table cellpadding=0 cellspacing=0 class=db-table align=center>\n<tr>" .
               "\n\t<th>Wine ID</th>" .
               "\n\t<th>Wine Name</th>" .
               "\n\t<th>Wine Type</th>" .
               "\n\t<th>Year</th>" .
               "\n\t<th>Price</th>" .
	       "\n\t<th>Region Name</th>" .
               "\n\t<th>Winery</th>\n</tr>";

         // Fetch each of the query rows
         while ($row = @ mysql_fetch_array($result))
         {
            // Print one row of results
            echo "\n<tr>\n\t<td>{$row["wine_id"]}</td>" .
                  "\n\t<td>{$row["wine_name"]}</td>" .
		  "\n\t<td>{$row["wine_type"]}</td>" .
                  "\n\t<td>{$row["year"]}</td>" .
		  "\n\t<td>{$row["cost"]}</td>" .
                  "\n\t<td>{$row["region_name"]}</td>" .
                  "\n\t<td>{$row["winery_name"]}</td>\n</tr>";
         } // end while loop body

         // Finish the <table>
         echo "\n</table>";
     } // end if $rowsFound body
   else
   echo" 0 records found<br>";
     // Report how many rows were found

  } // end of function

  // Connect to the MySQL server
  try
     {
      if (!($connection = @ mysql_connect($servername, $username, $password)))
      throw new Exception('Could not connect to database:'.mysql_error());
     }
     catch(Exception $e)
     {
         echo 'Caught Exception: ',  $e->getMessage(), "\n";
     }

  // Secure the user parameter 
  $regionName = mysqlclean($_GET, "regionName", 30, $connection);
  $wineType = mysqlclean($_GET, "wineType", 30, $connection);
  $price1 = mysqlclean($_GET, "price1", 30, $connection);
  $price2 = mysqlclean($_GET, "price2", 30, $connection);
  $year = mysqlclean($_GET, "year", 30, $connection);
  $wineName = mysqlclean($_GET, "wine_name", 30, $connection);
 
   try
	{
    	if (!mysql_select_db($dbname, $connection))
	throw new Exception('cant use' . dbname.':'.mysql_error());
	}
    catch(Exception $e)
     	{
         echo 'Caught Exception: ',  $e->getMessage(), "\n";
     	}

	echo "<p style='color:#99ff99;font-size:20px;'>Connected Successfully</p><br>";


  // Start a query ...
  $query = "SELECT  wine.wine_id, wine_name,wt.wine_type,region_name,cost,description,wine.year, winery_name
            FROM   winery, region, wine, inventory,wine_type wt
            WHERE  winery.region_id = region.region_id 
            AND    wine.winery_id = winery.winery_id
			AND    wine.wine_id = inventory.wine_id";

   // ... then, if the user has specified a region, add the regionName 
   // as an AND clause ...
   if (isset($regionName) && $regionName != "All")
     $query .= " AND region_name = \"{$regionName}\"";
   if (isset($wineType) && $wineType != "All")
     $query .= " AND wt.wine_type = \"{$wineType}\"";
	if (isset($year) && $year != " ")
     $query .= " AND year = \"{$year}\"";
    if (isset($wineName) && $wineName != " ")
     $query .= " AND wine_name like \"%{$wineName}%\"";

   if($price1!='' and $price2!='' and $year!='') {
	 $query .= " AND cost >= \"{$price1}\"";
	 $query .= " AND cost <= \"{$price2}\"";
	 $query .= " AND year <= \"{$year}\"";
   // ... and then complete the query.
         $query .= " ORDER BY wine_id";
}
   // run the query and show the results
   displayWinesList($connection, $query, $regionName, $wineType, $wineName);
   
   $result = $conn->query($query);
  try {
	    if(!$result)
 throw new Exception("Unable to execute query...");
  }
  catch(Exception $e)
     {
         echo 'Caught Exception: ',  $e->getMessage(), "\n";
     }

//mail
$file = fopen("http://cs99.bradley.edu/~akantumuchu/Assignment2/cgi-bin/singlelogin.txt", "r");
while (!feof($file)) {

$line= fgets($file);
$array = explode('#', $line);
$username=$array[0];
$email=$array[1];

}
fclose($file);


	        $to = $email;
	    	$subject = "Wine Store Query Results";
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
			$headers .= "From: <foodclubcs531@gmail.com>\r\n";
            
            if ($result->num_rows > 0)
            {
           		 $message = "<html><body>";
				 $message.="Hi,".$username."<br>";
				 $message.= "<span style='font-family:Times New Roman, Times, serif;color:red;'> {$result->num_rows} Records found matching your criteria	</span><br>";
				 $message.= "<h4><p style='color:#1876B6;text-align:center;font-size:20px;'>Wines belonging to: <b>$regionName</b> Type: <b>$wineType</b>  With Price between: <b>$price1</b> and <b>$price2</b>  And In The Year: <b>$year</b> <br></p></h4>";
              	 $message .= "<table border='1' align=center>
               	 <tr>
               	 	<th>Wine ID</th>
               		<th>Wine Name</th>
               		<th>Wine Type</th>
               		<th>Year</th>
               		<th>Price</th>
               		<th>Region_name</th>
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
                 	$message .= "<td>" . $row["wine_type"] . "</td>";
                 	$message .= "<td>" . $row["year"] . "</td>";
                 	$message .= "<td>" . $row["cost"] . "</td>";
                 	$message .= "<td>" . $row["region_name"] . "</td>";
                 	$message .= "</tr>";
                    }

	        $message .= "</table><br>";
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

?>
</body>
</html>