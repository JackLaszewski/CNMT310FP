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
print "<li><a href=\"login.php\">Login</a></li>";

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
    print "<li><a href=\"views/adminDashboard.php\">Admin Page</a></li>";
}

print "</ul>";
print "</nav>";

print "</body>";
print "</html>";
?>
