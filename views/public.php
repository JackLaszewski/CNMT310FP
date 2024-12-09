<?php
session_start(); 
require_once(__DIR__ . "/../page.php");
require_once("../WebServiceClient.php");
require_once("courseTableFunctions.php");

//get session variable user role to determine if its public or admin or if not logged in
//if not logged in set role to public

//get session variable user role to determine if its public or admin or if not logged in

//if student or admin redirect to student page
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == "student") { //do we want admin to also be able to access student page
    header("Location: student.php");
    exit();
}

$page = new MyNamespace\Page("public page");

print $page->addHeadElement("<link rel=\"stylesheet\" href=\"../CSS/style.css\">");

print $page->getTopSection();


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
print displayCourses($courses);

//link to return to main page
print "<br>";
print "<a href=\"../index.php\">Return to Main Page</a>";

print $page->getBottomSection();