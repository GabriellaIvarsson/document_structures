<?php include 'prefix.php';?>

<?php       
    $returnstring = "<calendar>";
   
    //new group
    $returnstring = $returnstring . "<form name='group'>";


    $groups = array();
    //add members to group
    /******************* Users *********************/
     // Get names of all groups
    $query = "SELECT  name
            FROM Groups";

    $result = mysql_query($query)
        or die("Query failed");

    while ($line = mysql_fetch_object($result)) {
        $name = utf8_encode($line->name);
        array_push($groups, $name);
    }
    $groups =  array_unique($groups);

    //want to show login form
    $returnstring = $returnstring . "<select name='group'>";
    foreach ($groups as $g) { //add groups to drop down
        $returnstring = $returnstring . "<option value='".$g."'>";
        $returnstring = $returnstring . $g."</option>";
    }
    $returnstring = $returnstring . "</select>";
    
    $returnstring = $returnstring . "</form>";


    $returnstring = $returnstring . "</calendar>";
    $returnstring =  utf8_encode($returnstring); 
?>
<?php include 'postfix.php';?>