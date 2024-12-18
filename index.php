<?php
session_start();
require_once("views/courseTableFunctions.php");
require_once("views/loginFunctionsClass.php");
require_once("page.php");
unset($_SESSION['error_message']);//clears error message when user returns to landing page


$page = new MyNamespace\Page("Index Page");

$page->addHeadElement("<link rel=\"stylesheet\" href=\"CSS/style.css\">");
$page->addHeadElement("<script src=\"JS/indexPage.js\"></script>");

// Output Top Section
print $page->getTopSection();

print "<header>";
print "<h1>Welcome to the landing page!</h1>";
print "</header>";
print "<nav>";
print "<ul>";
print "<li><a href=\"index.php\">Home</a></li>";

// Check if user is not logged in
if (!isset($_SESSION['user_role'])) {
    print "<li><a href=\"views/public.php\">Classes</a></li>";
    print "<li><a id =\"login-button\" href=\"login.php\">Login</a></li>";

} else {
    // Check if user is an admin and show the admin dashboard link
    if ($_SESSION['user_role'] == 'admin') {
        print "<li><a href=\"views/adminDashboard.php\">Admin Dashboard</a></li>";
    } elseif ($_SESSION['user_role'] == 'student') {
        print "<li><a href=\"views/studentView.php\">Student Classes</a></li>";
    }
    print "<li><a id =\"logout-button\" href=\"logout.php\">Logout</a></li>";
}
print "</ul>";
print "</nav>";


print "<div class=\"card-container\">";
print "<div class=\"class-card\">";
print "<h3>Classes</h3>";

$courses = getCourses();
print displayCourses($courses);
print "</div>";

// Required fields for validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {//dont do this
    $username = $_POST['username'];
    $password = $_POST['password'];
    Login($username,$password);
}

print "<div class=\"login-card\">";
print "<div class=\"login-container\">";
print "<h2>Login</h2>";
if (!empty($_SESSION['error_message'])) {
    print "<p class='error-message'>{$_SESSION['error_message']}</p>";
}
print "<form id=\"login-form\" method=\"POST\" >";
print "<label for=\"username\">Username:</label>";
print "<input type=\"text\" id=\"username\" name=\"username\" required>";
print "<label for=\"password\">Password:</label>";
print "<input type=\"password\" id=\"password\" name=\"password\" required>";
print "<button id=\"login-button\" type=\"submit\">Login</button>";
print "</form>";
print "</div>";

print "</div>";
print "</div>";

print $page->getBottomSection();

?>