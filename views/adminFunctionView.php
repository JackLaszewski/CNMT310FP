<?php

require_once("adminFunctionClass.php");

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../Errors/403.php");
    exit;
}

// Get the action from the session variable or query parameter
$action = isset($_GET['action']) ? $_GET['action'] : null;

// If a POST request is made with a delete action, use the delete_class action
//we dont want request method 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $action = 'delete_class';
}

print "<!DOCTYPE html>";
print "<html lang=\"en\">";
print "<head>";
print "<meta charset=\"UTF-8\">";
print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
print "<title>Admin Functions</title>";
print "<link rel=\"stylesheet\" href=\"../CSS/admin.css\">";
print "<script src=\"../JS/admin.js\"></script>";
print "</head>";
print "<body>";
print "<h1>Admin Functions</h1>";
print "<div class=\"admin-content\">";

$adminFunctions = new AdminFunctionClass();
//Switch case depending on what they choose in the admin dashboard
switch ($action) {
    case 'add_class':
        $adminFunctions->addClassTemplateView();
        break;
    case 'manage_classes':
        $adminFunctions->manageClassesView();
        break;
    case 'delete_class':
        if (isset($_POST['course_id'])) {
            $adminFunctions->deleteClassApiCall($_POST['course_id']);
        } else {
            print "<p>No course ID provided for deletion.</p>";
        }
        break;
    case 'manage_students':
        $adminFunctions->manageStudents();
        break;
    default:
        print "<p>Please select an action from the dashboard.</p>";
}

print "</div>";
print "</body>";
print "</html>";
?>
