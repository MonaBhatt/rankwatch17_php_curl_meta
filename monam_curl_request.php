<html>
<head>
  <title>Through CURL Request find meta keywords, meta description, title tag, IP address, Load
Time, HTTP Status, Internal & External Links of any URL.</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>
<div class="jumbotron text-center">
  <h1>Output Of Problem Statement 1</h1>
  <p>Through CURL Request find meta keywords, meta description, title tag, IP address, Load
Time, HTTP Status, Internal & External Links of any URL.</p> 
</div>
  
<div class="container">
  <div class="row">
	   <div class="col-md-12 text-center">
<?php 
	$web_url = $_POST["web_url"];
	echo "<h3> Details of the URL : $web_url</h3>";
	echo "<br/><br/>";
?>
<?php 

	include("lib/functions.php"); 
	$metaDetails = getMetaDetails($web_url); 
	echo "<b>Title:</b> ".$metaDetails['title']. '<br/><br/>'; // Printing The Title
	echo "<b>Description:</b> ".$metaDetails['description']. '<br/><br/>'; // Printing The Description
	// Checking if any keyword is returned or not if not Print NA
	if (empty($metaDetails['keywords']))  
	{
		echo "<b>Keywords :</b> NA" . '<br/><br/>';
	}
	else
	{
		echo "<b>Keywords: </b> " .$metaDetails['keywords']. '<br/><br/>';   
	}

	echo "<strong>IP Address: </strong>";
	echo getIPAddress($web_url);
	echo '<br/>';
	
	$htmlcode = getHttpHeaderCode($web_url);
	echo "<b>HTTP Code: </b> $htmlcode";
	echo '<br/>';
	
	$loadtime = getHttpLoadTime($web_url);
	echo "<b>URL Load Time:</b> $loadtime" . " " . "Seconds";
	echo "<br/>";
	
	echo "<b>Internal and External Link lists : </b>";
	echo "<br/><br/>"; 
	$urlResponce = getInternalExternalURL($web_url);
	
	echo "<ul>";
	if($urlResponce['status'] == 'Success')	{
		
		echo "<li><strong>Internal URL :</strong><ul>";
	
		
		foreach($urlResponce['internal_url'] as $internalurl)	{
			echo "<li>".$internalurl."</li>";
			
		}
		echo "</ul></li>";
		
		echo "<li><strong>External URL :</strong><ul>";
		echo '<br/>';
		
		foreach($urlResponce['external_url'] as $externalurl)	{
			echo "<li>".$externalurl."</li>";
		
		}
		
		echo "</ul></li>";
	}
	echo "</ul>";
	
?>
</div>
</div>
</div>
</body>
</html>
