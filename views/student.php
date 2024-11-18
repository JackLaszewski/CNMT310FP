<?php
require_once("studentFunctionClass.php");

if(!isset($_SESSION['user_role'] ) && $_SESSION['user_role'] !== "student"){ 
    header("Location: ../Errors/403.php");
    exit();
}
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

// nav
print "<nav>";
print "<ul>";
print "<li><a href=\"../index.php\">Home</a></li>";
print "<li><a href=\"public.php\">Classes</a></li>";
print "</ul>";
print "</nav>";

// Create an instance of StudentFunctionClass and call the view method
$studentFunctions = new StudentFunctionClass();

try {
    print $studentFunctions->studentViewClasses();
} catch (Exception $e) {
    print "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

print "<br>";
print "<a href=\"../index.php\" class=\"button-link\">Return to Main Page</a>";

print "</body>";
print "</html>";
?>