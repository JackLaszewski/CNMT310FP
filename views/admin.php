<?php
session_start(); 

require_once("WebServiceClient.php");

if(!isset($_SESSION['user_role'])){
    header("Login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $apikey = "";
    $apihash = "";
    
    //Get user input from form
    $coursename = $_POST['coursename'];
    $coursecode= $_POST['coursecode'];
    $coursenum= $_POST['coursenum'];
    $coursecredits= $_POST['coursecredits'];
    $coursedesc = $_POST['coursedesc'];
    $courseinstr = $_POST['courseinstr'];
    $meetingtimes = $_POST['meetingtimes'];
    $maxenroll = $_POST['maxenroll'];

    // Set up the web service client
    $url = "https://cnmt310.classconvo.com/classreg/";
    $client = new WebServiceClient($url);

    $action = "addcourse";
    $data = array("coursename" => $coursename, 
                  "coursecode" => $coursecode,
                  "coursenum" => $coursenum,
                  "coursecredits" => $coursecredits,
                  "coursedesc" => $coursedesc,
                  "courseinstr" => $courseinstr,
                  "meetingtimes" => $meetingtimes,
                  "maxenroll" => $maxenroll
    );
    $fields = array(
        "apikey" => $apikey,
        "apihash" => $apihash,
        "action" => $action,
        "data" => $data
    );
      // Set fields and send the request
      $client->setPostFields($fields);
      $result = $client->send();

      // Decode JSON response
    $jsonResult = json_decode($result);
    if (json_last_error() !== JSON_ERROR_NONE) {
        print "Result is not JSON";
        exit;
    }
    // Handle Result of adding a class
    if ($jsonResult->result == "Success"){
        print"<h1>Success</h1>";
    }
    else {
        $error_message = "Invalid Username or Password!";
    }
}

print "<!DOCTYPE html>";
print "<html lang=\"en\">";
print "<head>";
print "<meta charset=\"UTF-8\">";
print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
print "<title>Admin Page</title>";
print "<link rel=\"stylesheet\" href=\"style.css\">";
print "</head>";
print "<body>";
print "<form method=\"POST\" action=\"admin.php\">";
print "<label for=\"coursename\">Course name:</label>";
print "<input type=\"text\" id=\"coursename\" name=\"coursename\" required>";
print "<label for=\"coursecode\">Coursecode:</label>";
print "<input type=\"text\" id=\"coursecode\" name=\"coursecode\" required>";
print "<label for=\"coursenum\">Course Number</label>";
print "<input type=\"text\" id=\"coursenum\" name=\"coursenum\" required>";
print "<label for=\"coursecredits\">Number of Credits:</label>";
print "<input type=\"text\" id=\"coursecredits\" name=\"coursecredits\" required>";
print "<label for=\"coursedesc\">Course Description</label>";
print "<input type=\"text\" id=\"coursedesc\" name=\"coursedesc\" required>";
print "<label for=\"courseinstr\">Course Instructor</label>";
print "<input type=\"text\" id=\"courseinstr\" name=\"courseinstr\" required>";
print "<label for=\"meetingtimes\">Meeting Times</label>";
print "<input type=\"text\" id=\"meetingtimes\" name=\"meetingtimes\" required>";
print "<label for=\"maxenroll\">Max Enroll</label>";
print "<input type=\"text\" id=\"maxenroll\" name=\"maxenroll\" required>";
print "<button type=\"submit\">Add Class</button>";
print "</form>";
print "</body>";
print "</html>";

?>