<?php include 'prefix.php';?>

<?php
    $returnstring = "<calendar>";
    $returnstring = $returnstring . "<div id='not' value='".$_POST['formSave']."'>";
            $returnstring = $returnstring . "</div>";

    //Create new activity
    if($_POST['formCreate'] == "Create")
    {
        $name = utf8_decode($_POST['activityName']);
        $creator = utf8_decode($_POST['creator']);
        $date = utf8_decode($_POST['date']);
        $starttime = utf8_decode($_POST['starttime'].":00");
        $endtime = utf8_decode($_POST['endtime'].":00");
        $place = utf8_decode($_POST['place']);
        $link = utf8_decode($_POST['link']);
        $info = utf8_decode($_POST['info']);
        $group = utf8_decode($_POST['group']);

        $query = "SELECT starttime, endtime FROM Activities WHERE thedate = '".$date."' AND place = '".$place."'";

        // utför själva frågan. Om du har fel syntax får du felmeddelandet query failed
        $result = mysql_query($query)
        or die("Query failed");
        $collision = false;

        //Check that the time does not collide
        while ($line = mysql_fetch_object($result)) {
            $startexist = utf8_encode($line->starttime);
            $endexist = utf8_encode($line->endtime);
            if(($starttime >= $startexist && $starttime < $endexist) // starts within existing
            || ($endtime > $startexist && $endtime <= $endexist) //ends within existing
            || ($starttime < $startexist && $endtime > $endexist)) //wraps existing 
            {
                $collision = true;
            }
        }

        $query = "SELECT name FROM Activities WHERE name = '".$name."'";

        $result = mysql_query($query)
        or die("Query failed");

        if ($collision == true){ //collision in time and space
            $returnstring = $returnstring . "<div id='not' value='There is already an activity with this time and place.'>";
            $returnstring = $returnstring . "</div>";
        }
        else if(mysql_num_rows($result) != 0){ //Activity with this name exists.
            $returnstring = $returnstring . "<div id='not' value='There is already an acitivity with this name.'>";
            $returnstring = $returnstring . "</div>";
        }
        else{ //No collision
             /******************* Insert *********************/
             $query = "INSERT INTO Activities
            VALUES ('".$name."', '".$creator."', '".$date."', '".$starttime."', '"
                .$endtime."', '".$place."', '".$link."', '".$info."', '".$group."') ON DUPLICATE KEY UPDATE name = '".$name."'";

            $returnstring = $returnstring . "<div id='not' value='Created'>";
            $returnstring = $returnstring . "</div>";
            
            // utför själva frågan. Om du har fel syntax får du felmeddelandet query failed
            $result = mysql_query($query)
            or die("Query failed");
            header("refresh:2;url=schedule.php");
        }
    }
    //Save edited activity
    else if($_POST['formSave'] == "Save")
    {
        $originalName = utf8_decode($_POST['oName']);
        $name = utf8_decode($_POST['activityName']);
        $creator = utf8_decode($_POST['creator']);
        $date = utf8_decode($_POST['date']);
        $starttime = utf8_decode($_POST['starttime'].":00");
        $endtime = utf8_decode($_POST['endtime'].":00");
        $place = utf8_decode($_POST['place']);
        $link = utf8_decode($_POST['link']);
        $info = utf8_decode($_POST['info']);
        $group = utf8_decode($_POST['group']);

        /******************* Update *********************/
        $query = "UPDATE `Activities`
            SET name = '".$name."', creator = '".$creator."', thedate = '".$date."', starttime = '".$starttime."', 
            endtime = '".$endtime."', place = '".$place."', link = '".$link."', info = '".$info."', `group` = '".$group."' 
            WHERE `name` = '".$originalName."'";

        $returnstring = $returnstring . "<div id='not' value='Saved'>";
        $returnstring = $returnstring . "</div>";

        $result = mysql_query($query)
        or die("Query failed");
        header("refresh:2;url=schedule.php");
    }
    //Create new group or add members to existing group
    else if($_POST['formGroup1'] == "Create" || $_POST['formGroup2'] == "Save")
    {
        $query = "SELECT name FROM Users";

        // utför själva frågan. Om du har fel syntax får du felmeddelandet query failed
        $result = mysql_query($query)
        or die("Query failed");

        $theUsers = array();
        //Check that the time does not collide
        while ($line = mysql_fetch_object($result)) {
            array_push($theUsers, $line->name);
        }

        $group = trim(utf8_decode($_POST['group']));
        $users = trim(utf8_decode($_POST['users']));

        $users = split(",", $users); //get all users
        /******************* Insert *********************/
        foreach ($users as $user) {
            $user = trim($user);
            if(in_array($user, $theUsers)){
                $query = "INSERT INTO Groups
                VALUES ('".$group."', '".$user."') ON DUPLICATE KEY UPDATE name = '".$group."'";

                // utför själva frågan. Om du har fel syntax får du felmeddelandet query failed
                $result = mysql_query($query)
                or die("Query failed");
            }
        }

        $returnstring = $returnstring . "<div id='not' value='Saved'>";
        $returnstring = $returnstring . "</div>";
        header("refresh:2;url=schedule.php");
    }

    $returnstring = $returnstring . "</calendar>";
    $returnstring =  utf8_encode($returnstring); 
include 'postfix.php';?>