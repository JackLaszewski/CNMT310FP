<?php
require_once("../page.php");
require_once("adminFunctionClass.php");
require_once("studentFunctionClass.php");

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../Errors/403.php");
    exit;
}

// Get the action from the session variable or query parameter
$action = isset($_GET['action']) ? $_GET['action'] : null;

// need this???
if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST' && isset($_POST['course_id'])) {
    $action = 'delete_class';
}
//Do we need this still??
if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST' && isset($_POST['drop_course'])) {
    $ids = explode(",", $_POST['drop_course']);
    $student_id = $ids[0];
    $course_id = $ids[1];
    $action = 'drop_class';
}

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST' && isset($_POST['add_student_course']) && isset($_SESSION['admin_student_id'])) {
    $action = 'add_student_course';
}

$page = new MyNamespace\Page("Admin Functions");

$page->addHeadElement("<link rel=\"stylesheet\" href=\"../CSS/admin.css\">");
$page->addHeadElement("<script src=\"../JS/admin.js\"></script>");

print $page->getTopSection();


print "<h1>Admin Functions</h1>";
print "<div class=\"admin-content\">";

$adminFunctions = new AdminFunctionClass();
$studentFunctions = new StudentFunctionClass();
$output = ""; // Initialize the output variable

// Switch case depending on what they choose in the admin dashboard
switch ($action) {
    case 'add_class':
        $output = $adminFunctions->addClassTemplateView();
        print $output;
        break;
    case 'manage_classes':
        $output = $adminFunctions->manageClassesView();
        break;
    case 'delete_class':
        if (isset($_POST['course_id'])) {
            $output = $adminFunctions->deleteClassApiCall($_POST['course_id']);
        } else {
            $output = "<p>No course ID provided for deletion.</p>";
        }
        break;
    case 'manage_students':
        $output = $adminFunctions->manageStudents();
        break;
    case 'add_student_course':
        if (isset($_POST['add_student_course'])) {
            $output = "<p>Adding student to course: " . $_POST['add_student_course'] . "</p>";
            $studentFunctions->addStudentToCourse( $_SESSION['admin_student_id'],$_POST['add_student_course']);
        } else {
            $output = "<p>No student or course ID provided for addition.</p>";
        }
        break;
    default:
        $output = "<p>Please select an action from the dashboard.</p>";
}

// Print the output generated by the respective method
print $output;

print "</div>";

print $page->getBottomSection();
?>