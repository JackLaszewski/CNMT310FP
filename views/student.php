<?php
session_start(); 
require_once("../WebServiceClient.php");

//start of html because of print in validation
print "<!DOCTYPE html>";
print "<html lang=\"en\">";
print "<head>";
print "<meta charset=\"UTF-8\">";
print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
print "<title>Student Page</title>";
print "<link rel=\"stylesheet\" href=\"../CSS/style.css\">";
print "</head>";
print "<body>";

//get session variable user role to determine if its public or admin or if not logged in
//make sure to redirect to login if not logged in 
//add validation later
if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != "student"){
    $_SESSION['error_message'] = "Please login as a student."; //changed to session variable to store error message and display on login page
    exit(header('Location: ../login.php')); 
}

//since its public user doesnt need to login
$apikey = "api36";
$apihash = "kmpcpahw";


// Set up the web service client
$url = "https://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);

$action = "listcourses";
$wsData = array(
"apikey" => $apikey,
"apihash" => $apihash,
"action" => $action,
"data" => array()
);
$client->setPostFields($wsData);


$result = $client->send();
$jsonResult = json_decode($result);
if (json_last_error() !== JSON_ERROR_NONE) {
    print "Result is not JSON";
    exit;
}

//page header
print "<header>";
print "<h1>Student Page</h1>";
print "</header>";
//nav bar
print "<nav>";
print "<ul>";
print "<li><a href=\"../index.php\">Home</a></li>";
print "<li><a href=\"public.php\">Classes</a></li>";
print "<li><a href=\"../login.php\">Login</a></li>";
print "</ul>";
print "</nav>";

//if successful print list of courses
if ($jsonResult->result == "Success") {
    print "<table class='course-table'>";
    print "<tr><th>Course Name</th><th>Course Code</th><th>Course Number</th><th>Number of Credits</th><th>Course Description</th><th>Course Instructor</th><th>Meeting Times</th><th>Max Enrollment</th></tr>";
    foreach ($jsonResult->data as $key => $value) {
        $props = get_object_vars($jsonResult->data[$key]);
        print "<tr>";
        foreach ($props as $pkey => $pval) {
            if ($pkey != "id" && $pkey != "owner_id") { //id and owner_id are not needed and mess up table
                print "<td>" . $pval . "</td>";
            }
        }
        print "</tr>";
    }
    print "</table>";
} else {
    print "Failed to retrieve courses";
}

//link to return to main page
print "<br>";
print "<a href=\"../index.php\">Return to Main Page</a>";

print "</body>";
print "</html>";