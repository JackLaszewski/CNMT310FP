<?php
require_once("studentFunctionClass.php");

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../Errors/403.php");
    exit;
}
// Start of HTML
print "<!DOCTYPE html>";
print "<html lang=\"en\">";
print "<head>";
print "<meta charset=\"UTF-8\">";
print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
print "<title>Student Page</title>";
print "<link rel=\"stylesheet\" href=\"../CSS/student.css\">";
print "</head>";
print "<body>";

print "<header>";
print "<h1>Student Page</h1>";
print "</header>";

// Navigation bar
print "<nav>";
print "<ul>";
print "<li><a href=\"../index.php\">Home</a></li>";
print "<li><a href=\"public.php\">Classes</a></li>";
print "</ul>";
print "</nav>";

$studentFunctions = new StudentFunctionClass();
$studentFunctions->listCoursesView();

print "<br>";
print "<a href=\"../index.php\">Return to Main Page</a>";

print "</body>";
print "</html>";
?>