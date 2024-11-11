<?php
session_start();

require_once("adminFunctionClass.php");

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Get the action from the query parameter
$action = isset($_GET['action']) ? $_GET['action'] : null;

print "<!DOCTYPE html>";
print "<html lang=\"en\">";
print "<head>";
print "<meta charset=\"UTF-8\">";
print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
print "<title>Admin Functions</title>";
print "<link rel=\"stylesheet\" href=\"style.css\">";
print "</head>";
print "<body>";
print "<h1>Admin Functions</h1>";
print "<div class=\"admin-content\">";

$adminFunctions = new AdminFunctionClass();

switch ($action) {
    case 'add_class':
        // Display the form for adding a class
        $adminFunctions->addClassTemplateView();
        break;
    case 'manage_classes':
        // Placeholder for managing classes functionality
        $adminFunctions->manageClasses();
        break;
    case 'manage_students':
        // Placeholder for managing students functionality
        $adminFunctions->manageStudents();
        break;
    default:
        print "<p>Please select an action from the dashboard.</p>";
}

print "</div>";
print "</body>";
print "</html>";
?>
