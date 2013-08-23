<?php 
	function curPageName() {
		return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}
	//put XML content in a string
	$xmlstr=$returnstring;
	ob_end_clean();
	
	// Load the XML string into a DOMDocument
	$xml = new DOMDocument;
	$xml->loadXML($xmlstr);
	
	// Make a DOMDocument for the XSL stylesheet
	$xsl = new DOMDocument;
	
	// See which user agent is connecting
	$UA = getenv('HTTP_USER_AGENT');
	if (preg_match("/Symbian/", $UA) | preg_match("/Opera/", $UA) | preg_match("/Nokia/", $UA)) 
	{
	
		// if a mobile phone, use a wml stylesheet and set appropriate MIME type
		header("Content-type:text/vnd.wap.wml;charset=iso-8859-1");
		$xsl->load('calendar-wml.xsl');
	} 
	else 
	{
		// if not a mobile phone, use a html stylesheet
		header("Content-type:text/html;charset=iso-8859-1");
		$xsl->load('calendar-html.xsl');

	}

	// Make the transformation and print the result
	$proc = new XSLTProcessor;
	$proc->importStyleSheet($xsl); // attach the xsl rules
	$proc->setParameter('', 'currentPage', curPageName()); //Send current page
	//send user cookie with
	if(isset($_COOKIE["theUser"]) && $_COOKIE["theUser"] != ''){
		$proc->setParameter('', 'theUser', $_COOKIE["theUser"]);
		if(curPageName() != "logout.php"){
			setcookie("theUser", $_COOKIE["theUser"]);
		}
	}
	else{
		$proc->setParameter('', 'theUser', '');
	}
	echo utf8_decode($proc->transformToXML($xml));
	?>
