<?php 
	/*
		Author : Monam Bhatt
		Object : Get Total load time in seconds of given website url using curl
	*/
	
	function getHttpLoadTime($url) {
		$handle = curl_init($url);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

		// gtting the Page Contents into Response
		$response = curl_exec($handle);

		// Get Loadtime Using Curl Inbuilt Function
		//CURLINFO_TOTAL_TIME - Total transaction time in seconds for last transfer
		$loadtime = curl_getinfo($handle, CURLINFO_TOTAL_TIME);
		curl_close($handle);
		return $loadtime;
	}
	
	
	/*
		Author : Monam Bhatt
		Object : Get meta title,dexcription,keywords of given website url using curl
	*/
	
	function getMetaDetails($url)
	{
		$ch = curl_init(); //Initializing Curl

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$html = curl_exec($ch); //Executing CURL
		curl_close($ch); // Closing Curl
		
		// Starting of Parsing 
		$doc = new DOMDocument(); //creating New Dom Document
		@$doc->loadHTML($html); // Loading HTML Source in docs
		$nodes = $doc->getElementsByTagName('title'); // Fetching the attributes of Title Tags 
		$metaDetails = [];
		//Putting Details into a variable
		$metaDetails['title'] = $nodes->item(0)->nodeValue; // Fetching title 
		
		$metas = $doc->getElementsByTagName('meta'); //getting Elements with the tags meta
		// Seperating meta description and meta keyword  with each other with running a loop.
		for ($i = 0; $i < $metas->length; $i++)  
		{
			$meta = $metas->item($i);
			if($meta->getAttribute('name') == 'description') //Fetching description Attribute
			$metaDetails['description'] = $meta->getAttribute('content'); //Fetching meta description
			if($meta->getAttribute('name') == 'keywords') //Fetching attributes with the Attribute "keywords" 
			$metaDetails['keywords'] = $meta->getAttribute('content'); // Loading keyword content into a variable
		}
		
		return $metaDetails; // Returning meta detail array
	}
	
	/*
		Author : Monam Bhatt
		Object : Get IP Adress of given website url using curl
	*/
	
	
	function getIPAddress($url)	{
		
		$temp_file = fopen('php://temp', 'r+'); // Open a temp file
		$ch = curl_init($url);         // Initilizing Curl
		curl_setopt($ch, CURLOPT_VERBOSE, true);  
		curl_setopt($ch, CURLOPT_STDERR, $temp_file);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch); // Executing Curl
		curl_close($ch); // Closing Curl
		
		rewind($temp_file); // rewind the position of the file pointer to the beginning of the file.
		$str = fread($temp_file, 8192); // Reading IP address
		$regex = '/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/'; // Regex for the Valid IP adress
		if (preg_match_all($regex, $str, $matches)) {
			$ips = array_unique($matches[0]);
		} else {
			$ips =  false;
		}
		
		fclose($temp_file); // Closing temp file
		
		return  end($ips);  // returning IP address
		
	}
	
	/*
		Author : Monam Bhatt
		Object : Get Http Header Code of given website url using curl
		
	*/
	
	function getHttpHeaderCode($url) {
		
		$handle = curl_init($url);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

		// Get the HTML Code in the response
		$response = curl_exec($handle);

		// Checking http code through curl Inbuilt Function
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		curl_close($handle);
		
		return $httpCode;
	}
	
	/*
		Author : Monam Bhatt
		Object : Get Internal External URL of given website url using curl
	*/
	
	function getInternalExternalURL($url)	{
		
		$internalExternalURL = [];
		$userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
		// make the cURL request to $target_url
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$html= curl_exec($ch);
		if (!$html) {
			$internalExternalURL['status'] = "Error";
			$internalExternalURL['error_number'] = curl_errno($ch);
			$internalExternalURL['error'] = curl_error($ch);
			
		}
		else 
		{	
			$internalExternalURL['status'] = "Success";
			// parse the html into a DOMDocument
			$dom = new DOMDocument();
			@$dom->loadHTML($html);
			
			// grab all the on the page
			$xpath = new DOMXPath($dom);
			$hrefs = $xpath->evaluate("/html/body//a");

			for ($i = 0; $i < $hrefs->length; $i++) {
				$href = $hrefs->item($i);
				$href_url = $href->getAttribute('href');
				$link = checkLink($href_url,$url);
				if(!empty($link) && $link['type'] == 'internal')
					$internalExternalURL['internal_url'][] = $link['url'];
				else if(!empty($link) && $link['type'] == 'external')
					$internalExternalURL['external_url'][] = $link['url'];
			}
		}
		return $internalExternalURL;
	}
	
	function checkLink($href_url,$url) {
		
		$link = [];
		if(filter_var($href_url, FILTER_VALIDATE_URL)){
					 $position = strpos($href_url, $url);

					  if($position !== FALSE)
					  {
						$link['type'] =	'internal'; 
						$link['url'] =	$href_url; 					
					  }
					  else
					  {
						$link['type'] =	'external' ;
						$link['url'] =	$href_url; 					
					  }
				 
			}
			
		return	$link;	 
	}
	
	
	
?>