<?php
session_start();
require_once("views/courseTableFunctions.php");
unset($_SESSION['error_message']);//clears error message when user returns to landing page

print "<!doctype html>";
print "<html lang=\"en\">";
print "<head>";
print "<title>Landing Page</title>";
print "<link rel=\"stylesheet\" type=\"text/css\" href=\"CSS/style.css\">";
print "</head>";
print "<body>";

print "<header>";
print "<h1>Welcome to the landing page!</h1>";
print "</header>";
print "<nav>";
print "<ul>";
print "<li><a href=\"index.php\">Home</a></li>";
print "<li><a href=\"views/public.php\">Classes</a></li>";

// Check if user is not logged in
if (!isset($_SESSION['user_role'])) {
    print "<li><a href=\"login.php\">Login</a></li>";

    print "<li><a href=\"views/public.php\">Classes</a></li>";
} else {
    // Check if user is an admin and show the admin dashboard link
    if ($_SESSION['user_role'] == 'admin') {
        print "<li><a href=\"views/adminDashboard.php\">Admin Dashboard</a></li>";
    }
    elseif($_SESSION['user_role'] == 'student'){
        print "<li><a href=\"views/student.php\">Classes</a></li>";
    }
    print "<li><a href=\"logout.php\">Logout</a></li>";
}
print "</ul>";
print "</nav>";


print "<div class=\"card-container\">";
print "<div class=\"class-card\">";
print "<h3>Card 1</h3>";
print "<p>This is a card.</p>";

//testing functions
$courses = getCourses();
displayCourses($courses);

print "</div>";

print "<div class=\"login-card\">";
print "<h3>Card 2</h3>";
print "<p>This is another card.</p>";
print "</div>";
print "</div>";


print "</body>";
print "</html>";
?>
