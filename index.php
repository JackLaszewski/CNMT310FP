<?php
session_start();
require_once("views/courseTableFunctions.php");
require_once("views/loginFunctionsClass.php");
require_once("page.php");

// Clear error messages when returning to the landing page
unset($_SESSION['error_message']);

// Initialize the Page object
$page = new MyNamespace\Page("Landing Page");
$page->addHeadElement("<link rel=\"stylesheet\" type=\"text/css\" href=\"CSS/style.css\">");
$page->addHeadElement("<script src=\"JS/indexPage.js\" defer></script>");

// Start generating the page
print $page->getTopSection();

print "<header>";
print "<h1>Welcome to the Landing Page!</h1>";
print "</header>";

print "<nav>";
print "<ul>";
print "<li><a href=\"index.php\">Home</a></li>";

// Check if the user is logged in
if (!isset($_SESSION['user_role'])) {
    print "<li><a href=\"views/public.php\">Classes</a></li>";
} else {
    // Display admin dashboard if user is an admin
    if ($_SESSION['user_role'] == 'admin') {
        print "<li><a href=\"views/adminDashboard.php\">Admin Dashboard</a></li>";
    } elseif ($_SESSION['user_role'] == 'student') {
        print "<li><a href=\"views/studentView.php\">Student Classes</a></li>";
    }
    print "<li><a id=\"logout-button\" href=\"logout.php\">Logout</a></li>";
}
print "</ul>";
print "</nav>";

print "<div class=\"card-container\">";

// Classes Card
print "    <div class=\"class-card\">";
print "        <h3>Classes</h3>";
$courses = getCourses();
displayCourses($courses); // Assuming this outputs the course list as a table
print "    </div>";

// Login Card
print "<div class=\"login-card\">";
print "<div class=\"login-container\">";
print "<h2>Login</h2>";
if (!empty($_SESSION['error_message'])) {
    print "<p class='error-message'>" . htmlspecialchars($_SESSION['error_message']) . "</p>";
}
print "<form id=\"login-form\" method=\"POST\">";
print "<label for=\"username\">Username:</label>";
print "<input type=\"text\" id=\"username\" name=\"username\" required>";
print "<label for=\"password\">Password:</label>";
print "<input type=\"password\" id=\"password\" name=\"password\" required>";
print "<button id=\"login-button\" type=\"submit\">Login</button>";
print "</form>";
print "/div>";
print "</div>";

print "</div>";

print $page->getBottomSection();