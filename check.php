<?php include 'prefix.php';?>
<!DOCTYPE calendar SYSTEM "http://www.student.itn.liu.se/~gabiv132/TNM065/projekt/calendar.dtd">

<?php
        
    $users = array();
    $returnstring = "<calendar>";

    /******************* Users *********************/
    //Get all users
    $query = "SELECT  *
            FROM Users";

    $result = mysql_query($query)
        or die("Query failed");

    // Loop and save result
    while ($line = mysql_fetch_object($result)) {
        $name = utf8_encode($line->name);
        $password = utf8_encode($line->password);
        $email = utf8_encode($line->email);

        array_push($users, $name);
        array_push($users, $password); 
    }

    //Log in user
    if($_POST['formSubmit'] == "Log in")
    {
        $username = trim(utf8_encode($_POST['username']));
        $password = trim(utf8_encode($_POST['password']));
        $found = false;
        
        for($i=0; $i<sizeof($users); $i=$i+2){

            if(strtolower($users[$i]) == strtolower($username) && $users[$i+1] == $password){ //If user found
                $found = true;
                setcookie("theUser", $users[$i], time()+3600); //set cookie to keep logged in, expires in one hour
                header("refresh:0;url=index.php");
            }
        }
        if($found == false){
            $returnstring = $returnstring . "<div id='not' value='Incorrect login'>";
            $returnstring = $returnstring . "</div>";
            header("refresh:2;url=login.php");
        }
    }
    
    //register new user
    if($_POST['formNew'] == "Register"){
        $username = trim($_POST['username']);
        $password = utf8_decode($_POST['password']);
        $password2 = utf8_decode($_POST['password2']);
        $email = trim(utf8_decode($_POST['email']));

        if($password != $password2){
            $returnstring = $returnstring . "<div id='not' value='Password does not match'>";
            $returnstring = $returnstring . "</div>";
            header("refresh:2;url=login.php");
        }
        else{
            // Insert the new user into database
            $query = "INSERT INTO Users (name, password, email) VALUES ('".$username."', '".$password."', '".$email."')";

            // utför själva frågan. Om du har fel syntax får du felmeddelandet query failed
            $result = mysql_query($query)
                or die("Query failed");

            setcookie("theUser", $_POST['username'], time()+3600); //set cookie to keep logged in, expires in one hour
            header("refresh:0;url=index.php");
        }
    }

    $returnstring = $returnstring . "</calendar>";
    $returnstring =  utf8_encode($returnstring); 

include 'postfix.php';?>