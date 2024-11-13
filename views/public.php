<?php
session_start(); 
require_once("../WebServiceClient.php");
require_once("courseTableFunctions.php");

//get session variable user role to determine if its public or admin or if not logged in
//if not logged in set role to public

//get session variable user role to determine if its public or admin or if not logged in

//if student or admin redirect to student page
 if($_SESSION['user_role'] == "student" || $_SESSION['user_role'] == "admin"){ //do we want admin to also be able to access student page
    header("Location: student.php");
    exit();
}

//start of html
print "<!DOCTYPE html>";
print "<html lang=\"en\">";
print "<head>";
print "<meta charset=\"UTF-8\">";
print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
print "<title>Public Page</title>";
print "<link rel=\"stylesheet\" href=\"../CSS/style.css\">";
print "</head>";
print "<body>";

//page header
print "<header>";
print "<h1>Public Page</h1>";
print "</header>";
//nav bar
print "<nav>";
print "<ul>";
print "<li><a href=\"../index.php\">Home</a></li>";
print "<li><a href=\"public.php\">Classes</a></li>";
print "<li><a href=\"../login.php\">Login</a></li>";
print "</ul>";
print "</nav>";

$courses = getCourses();
displayCourses($courses);

//link to return to main page
print "<br>";
print "<a href=\"../index.php\">Return to Main Page</a>";

print "</body>";
print "</html>";
