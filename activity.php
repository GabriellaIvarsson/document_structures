<?php include 'prefix.php';?>
<!DOCTYPE calendar SYSTEM "http://www.student.itn.liu.se/~gabiv132/TNM065/projekt/calendar.dtd">

<?php
    $returnstring = "<calendar>";
    $acitivityName = ($_POST['activityName']);
    //If pressed button to subscribe to activity
    if($_POST['button'] == "Subscribe")
    {
        /******************* Insert *********************/
        $query = "INSERT INTO Subscriptions
            VALUES ('".utf8_decode($acitivityName)."', '".$_COOKIE["theUser"]."') ON DUPLICATE KEY UPDATE activity = '".utf8_decode($acitivityName)."'";

        // utför själva frågan. Om du har fel syntax får du felmeddelandet query failed
        $result = mysql_query($query)
            or die("Query failed");   
        
        //print message to screen
        $returnstring = $returnstring . "<div id='not' ". utf8_encode("value='You are now subscribing to the acitivity ").utf8_encode($acitivityName).".'>";
        $returnstring = $returnstring . "</div>";
        $returnstring = $returnstring . "</calendar>";
        $returnstring = $returnstring; 
        //load myschedule when done
        header("refresh:2;url=myschedule.php");
    }
    
    //If pressed button to edit activity
    if($_POST['button2'] == "Edit")
    {
        /******************* Activities *********************/
        // Get the activity to edit
        $query = "SELECT  *
                FROM Activities WHERE name='".utf8_decode($acitivityName)."'";

        // utför själva frågan. Om du har fel syntax får du felmeddelandet query failed
        $result = mysql_query($query)
            or die("Query failed");
            
        while ($line = mysql_fetch_object($result)) {
            // save values
            $name = utf8_encode($line->name);
            $creator = utf8_encode($line->creator);
            $date = utf8_encode($line->thedate);
            $starttime = utf8_encode($line->starttime);
            $endtime = utf8_encode($line->endtime);
            $place = utf8_encode($line->place);
            $link = utf8_encode($line->link);
            $info = utf8_encode($line->info);
            $group = utf8_encode($line->group);
        }
        //get all groups available
        $groups = array();
        /******************* Users *********************/
         // get all names of groups
        $query = "SELECT  name
                FROM Groups";

        // utför själva frågan. Om du har fel syntax får du felmeddelandet query failed
        $result = mysql_query($query)
            or die("Query failed");

            // loopa över alla resultatrader och skriv ut en motsvarande tabellrad
        while ($line = mysql_fetch_object($result)) {
            // lagra innehållet i en tabellrad i variabler
            $temp = utf8_encode($line->name);
            array_push($groups, $temp);
        }
        $groups =  array_unique($groups);

        //create form
        $returnstring = $returnstring . "<form name='edit'>";
        $returnstring = $returnstring . "<label>Name: </label>";
        $returnstring = $returnstring . "<input type='text' name='activityName' value='".utf8_decode($acitivityName)."'/><br/>";
        $returnstring = $returnstring . "<label>Date: </label>";
        $returnstring = $returnstring . "<input type='date' name='date' value='".$date."'/><br/>";
        $returnstring = $returnstring . "<label>Time: </label>";
        $returnstring = $returnstring . "<input type='time' name='starttime' value='".$starttime."'/>";
        $returnstring = $returnstring . "<input type='time' name='endtime' value='".$endtime."'/><br/>";
        $returnstring = $returnstring . "<label>Place: </label>";
        $returnstring = $returnstring . "<input type='text' name='place' value='".$place."'/><br/>";
        $returnstring = $returnstring . "<label>Created by: </label>";
        $returnstring = $returnstring . "<input type='text' name='creator' value='".$creator."'/><br/>";
        $returnstring = $returnstring . "<label>Link: </label>";
        $returnstring = $returnstring . "<input type='text' name='link' value='".$link."'/><br/>";
        $returnstring = $returnstring . "<label>Other info: </label>";
        $returnstring = $returnstring . "<input type='text' name='info' value='".$info."'/><br/>";

        //create drop down for groups
        $returnstring = $returnstring . "<label>Group: </label>";
        $returnstring = $returnstring . "<select name='group'>";
        foreach ($groups as $g) {
            if($g == $group){ // if the current group of the acitivity
                $returnstring = $returnstring . "<option value='".$g."' selected='selected'>";
                $returnstring = $returnstring . $g."</option>";
            }
            else{
                $returnstring = $returnstring . "<option value='".$g."'>";
                $returnstring = $returnstring . $g."</option>";
            }
        }
        $returnstring = $returnstring . "</select>";
        
        $returnstring = $returnstring . "</form>";
        $returnstring = $returnstring . "</calendar>";
        $returnstring =  utf8_encode($returnstring); 
    }
    ?>
<?php include 'postfix.php';?>