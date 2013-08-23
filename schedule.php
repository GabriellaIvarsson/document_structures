<?php include 'prefix.php';?>

<?php
    /******************* Activities *********************/
		// Get all activities
    $query = "SELECT  *
            FROM Activities ORDER BY thedate ASC, starttime ASC";

    $result = mysql_query($query)
        or die("Query failed");
        
    $returnstring = "<calendar>";
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

    $returnstring = $returnstring . "</calendar>";
    $returnstring =  utf8_encode($returnstring); 

include 'postfix.php';?>