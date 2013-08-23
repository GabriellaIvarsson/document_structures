<?php include 'prefix.php';?>

<?php
        
    $returnstring = "<calendar>";
    //get search string
    $search = utf8_decode($_POST['search']);

    // Get all activities that have an attribute like the search string.
    $query = "SELECT  *
            FROM Activities WHERE name LIKE '%".$search."%' OR creator LIKE '%".$search."%' OR creator LIKE '%".$search."%' 
            OR place LIKE '%".$search."%' OR info LIKE '%".$search."%'";

    $result = mysql_query($query)
        or die("Query failed");

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

        // add one word to the result
        // concatenate strings with "."
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