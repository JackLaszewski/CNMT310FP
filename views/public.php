<?php
session_start(); 
require_once(__DIR__ . "/../page.php");
require_once("../WebServiceClient.php");
require_once("courseTableFunctions.php");

// Redirect if the user is a student
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == "student") {
    header("Location: student.php");
    exit();
}

$page = new MyNamespace\Page("Public Page");

// Add custom styles and JavaScript
$page->addHeadElement("<link rel=\"stylesheet\" href=\"../CSS/public.css\">"); // Link to specific public page styles
$page->addHeadElement("<script src=\"../JS/public.js\"></script>");

print $page->getTopSection();

// Page Header
print "<header class='public-header'>";
print "<h1>Explore Our Courses</h1>";
print "<p>Browse the available classes and discover what interests you!</p>";
print "</header>";

// Navigation Bar
print "<nav class='public-nav'>";
print "<ul>";
print "<li><a href=\"../index.php\">Home</a></li>";
print "<li><a href=\"public.php\">Classes</a></li>";
print "<li><a href=\"../login.php\">Login</a></li>";
print "</ul>";
print "</nav>";

// Main Content Section
print "<main class='public-main'>";
print "<h2>Available Classes</h2>";

$courses = getCourses();
print displayCourses($courses); // Display courses in a card layout

print "</main>";

// Link to Return to Main Page
print "<footer class='public-footer'>";
print "<a href=\"../index.php\">Return to Main Page</a>";
print "</footer>";

print $page->getBottomSection();
?>