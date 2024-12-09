<?php
require_once("../page.php");
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../Errors/403.php");
    exit;
}

$page = new MyNamespace\Page("Admin Dashboard");

$page->addHeadElement("<link rel=\"stylesheet\" href=\"../CSS/admin.css\">");

print $page->getTopSection();

print "<div class=\"dashboard-container\">";
print "<h1>Admin Dashboard</h1>";
print "<div class=\"dashboard-links\">";
//Add Class
print "<a href=\"../views/adminFunctionView.php?action=add_class\" class=\"dashboard-link add-class\">Add Class</a>";
//Manage Class
print "<a href=\"../views/adminFunctionView.php?action=manage_classes\" class=\"dashboard-link manage-classes\">Manage Classes</a>";
//Manage Students
print "<a href=\"../views/adminFunctionView.php?action=manage_students\" class=\"dashboard-link manage-students\">Manage Students</a>";
print "</div>";
print "<p><a href=\"../index.php\">Return to Main Page</a></p>";
print "</div>";

print $page->getBottomSection();
?>
