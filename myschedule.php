<?php include 'prefix.php';?>

<?php
    $subsriptions = array();
    setcookie("theUser", utf8_encode($users[$i])); //set cookie to keep logged in

    if($_COOKIE["theUser"] == ''){ //Not logged in
        $returnstring = "<calendar>";
        $returnstring = $returnstring . "<div id='not' value='You must be logged in to see your schedule.'>";
        $returnstring = $returnstring . "</div>";
        $returnstring = $returnstring . "</calendar>";
        $returnstring =  $returnstring; 
    }
    else{ //Logged in
        /******************* Subscriptions *********************/
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

        /******************* Activities *********************/
   		// Get all activities
        $query = "SELECT  *
            FROM Activities ORDER BY thedate ASC, starttime ASC";

        $result = mysql_query($query)
            or die("Query failed");
            
        $returnstring = "<calendar>";
        $returnstring = $returnstring . "<form name='pdf'>";
        $returnstring = $returnstring . "</form>";

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
    	        $returnstring = $returnstring . "<activity>";
    	        $returnstring = $returnstring . "<name>$name</name>"; 

    	        $returnstring = $returnstring . "<creator>";
    	        $returnstring = $returnstring . "<user>";
    	        $returnstring = $returnstring . "<name>$creator</name>";
    	        $returnstring = $returnstring . "</user>";
    	        $returnstring = $returnstring . "</creator>";

    	        $returnstring = $returnstring . "<date>$date</date>";
    	        $returnstring = $returnstring . "<starttime>$starttime</starttime>";
    	        $returnstring = $returnstring . "<endtime>$endtime</endtime>"; 
    	        $returnstring = $returnstring . "<place>$place</place>";
    	        $returnstring = $returnstring . "<link>$link</link>";
    	        $returnstring = $returnstring . "<info>$info</info>";

    	        $returnstring = $returnstring . "<group>";
    	        $returnstring = $returnstring . "<name>$group</name>";
    	        $returnstring = $returnstring . "</group>";

    	        $returnstring = $returnstring . "</activity>";
            }
        }


        $returnstring = $returnstring . "</calendar>";
        $returnstring =  utf8_encode($returnstring); 
    }
?>
<?php include 'postfix.php';?>