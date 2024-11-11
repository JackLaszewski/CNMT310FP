<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

print "<!DOCTYPE html>";
print "<html lang=\"en\">";
print "<head>";
print "<meta charset=\"UTF-8\">";
print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
print "<title>Admin Dashboard</title>";
print "<link rel=\"stylesheet\" href=\"style.css\">";
print "</head>";
print "<body>";
print "<h1>Admin Dashboard</h1>";

print "<a href=\"../views/adminFunctionView.php?action=add_class\">Add Class</a><br>";
print "<a href=\"../views/adminFunctionView.php?action=manage_classes\">Manage Classes</a><br>";
print "<a href=\"../views/adminFunctionView.php?action=manage_students\">Manage Students</a><br>";

print "</body>";
print "</html>";
?>
