<?php
session_start();

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
} else {
    print "<li><a href=\"logout.php\">Logout</a></li>";

    // Check if user is an admin and show the admin dashboard link
    if ($_SESSION['user_role'] == 'admin') {
        print "<li><a href=\"views/adminDashboard.php\">Admin Dashboard</a></li>";
    }
}

print "</ul>";
print "</nav>";

print "</body>";
print "</html>";
?>
