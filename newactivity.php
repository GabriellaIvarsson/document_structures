<?php include 'prefix.php';?>

<?php       
    $returnstring = "<calendar>";
    $groups = array();

    /******************* Users *********************/
     // Get all groups
    $query = "SELECT  name
            FROM Groups";

    $result = mysql_query($query)
        or die("Query failed");

    while ($line = mysql_fetch_object($result)) {
        $name = utf8_encode($line->name);
        array_push($groups, $name);
    }
    $groups =  array_unique($groups); //only get one of each group name

    //want to show form for new activity
    $returnstring = $returnstring . "<form name='new'>";
    $returnstring = $returnstring . "<select name='group'>";
    foreach ($groups as $g) { //add groups to dropdown
        $returnstring = $returnstring . "<option value='".$g."'>";
        $returnstring = $returnstring . $g."</option>";
    }
    $returnstring = $returnstring . "</select>";
    
    $returnstring = $returnstring . "</form>";


    $returnstring = $returnstring . "</calendar>";
    $returnstring =  utf8_encode($returnstring); 
include 'postfix.php';?>