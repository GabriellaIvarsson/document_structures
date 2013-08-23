<?php
	ini_set('display_errors','1');
	ini_set('display_startup_errors','1');
	error_reporting (E_ALL);
	ob_start();
	header("Content-type:text/xml;charset=iso-8859-1");
?>
<!DOCTYPE calendar SYSTEM "http://www.student.itn.liu.se/~gabiv132/TNM065/projekt/calendar.dtd">
<!DOCTYPE html>
<?php

	// connect to database
	$link = mysql_connect("localhost", "gabiv132", "gabiv132-2012")
	    or die("Could not connect");
	// choose database
	mysql_select_db("gabiv132")
	    or die("Could not select database");

	//***********Send email*************
	if($_COOKIE["theUser"] != ''){
		$theUser = $_COOKIE["theUser"];
		$cookieName = (string)str_replace(" ", "", $theUser);
		$date = time();//mktime(0,0,0,12,2,2012);
	    $dateDiff = $_COOKIE[$cookieName] - $date;
		$fullDays = $dateDiff/(60*60*24);

		//If there has been more than one day since last email was sent
	    if($fullDays >= 1 || !isset($_COOKIE[$cookieName])){
			//****Check subscriptions****
			$subsriptions = array();

			// Get all subscriptions
	        $query = "SELECT  *
	                FROM Subscriptions";

	        $result = mysql_query($query)
	            or die("Query failed");

	        while ($line = mysql_fetch_object($result)) {
	            // lagra innehållet i en tabellrad i variabler
	            $activity = utf8_encode($line->activity);
	            $user = utf8_encode($line->user);

	            if($user == $_COOKIE["theUser"]){ //If your subscription
	                array_push($subsriptions, $activity);
	            }
	        }

	        //****Get all activities
			$query = "SELECT  *
		            FROM Activities";

		    $result = mysql_query($query)
		        or die("Query failed");

		    $message = "Hi ".$theUser.",\nHere are your subscriptions on Calendar.\n\n";
		    while ($line = mysql_fetch_object($result)) {
	            $name = utf8_encode($line->name);
	            $creator = utf8_encode($line->creator);
	            $date = utf8_encode($line->thedate);
	            $starttime = utf8_encode($line->starttime);
	            $endtime = utf8_encode($line->endtime);
	            $place = utf8_encode($line->place);
	            $link = utf8_encode($line->link);
	            $info = utf8_encode($line->info);
	            $group = utf8_encode($line->group);

	            //if the logged in user is the creator or subscribes to the activity
	            if($creator == $_COOKIE["theUser"] || in_array($name, $subsriptions)){
		 			// Add to message
					$message = $message . "Name of activity: " . $name . "\n";
					$message = $message . "Creator: " . $creator . "\n";
					$message = $message . "Date: " . $date . "\n";
					$message = $message . "Start: " . $starttime . "\n";
					$message = $message . "End: " . $endtime . "\n";
					$message = $message . "Place: " . $place . "\n";
					$message = $message . "Link: " . $link . "\n";
					$message = $message . "Info: " . $info . "\n";
					$message = $message . "Group: " . $group . "\n\n";
	            }
	        }
	        
	        $message = $message . "\n\nRegards,\nCalendar\n";
			$cookieName = (string)str_replace(" ", "", $theUser);
	        setcookie($cookieName, time()); //set cookie to keep logged in
			
		   

			// In case any of our lines are larger than 70 characters, we should use wordwrap()
			$message = wordwrap($message, 70);

			$query = "SELECT  email
		            FROM Users WHERE name='".$theUser."'";

		    $result = mysql_query($query)
		        or die("Query failed");

		    $line = mysql_fetch_object($result);
		    $email = utf8_encode($line->email);
			// Send
			mail($email, 'Your subscriptions on Calendar.', $message);
		}
	}
?>