<?php
session_start();
require_once("../views/studentFunctionClass.php");
require_once("../page.php");

// Check if the student is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== "student") {
    header("Location: ../Errors/403.php");
    exit();
}

// Fetch student_id from session
$student_id = $_SESSION['student_id'] ?? null;

if (!$student_id) {
    die("Student ID is missing. Please log in again.");
}

$studentFunctions = new StudentFunctionClass();
$error_message = "";
$success_message = "";

// Define the required fields
$required = array("course_id");

// Validate the POST data
foreach ($required as $req) {
    if (!isset($_POST[$req]) || empty($_POST[$req])) {
        $_SESSION['errors'][] = "Please select a course.";
        header("Location: studentView.php"); // Redirect back with errors
        exit();
    }
}

// Process the form submission if validation passes
if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    try {
        $success_message = $studentFunctions->addStudentToCourse($student_id, $course_id);
    } catch (Exception $e) {
        $_SESSION['errors'][] = $e->getMessage();
        header("Location: studentView.php"); // Redirect back with errors
        exit();
    }
}

// Retrieve course data
try {
    $availableCoursesHTML = $studentFunctions->studentViewClasses($student_id);
    $enrolledCoursesHTML = $studentFunctions->listStudentCourses($student_id);
} catch (Exception $e) {
    $error_message = $e->getMessage();
}

// Initialize Page object
$page = new MyNamespace\Page("Student View");
$page->addHeadElement("<link rel=\"stylesheet\" href=\"../CSS/student.css\">");

// Output Top Section
print $page->getTopSection();

// Output Body Content
print "<div class=\"container\">";
print "<h1>Student View</h1>";

if (!empty($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $error) {
        print "<p class='error-message'>" . htmlspecialchars($error) . "</p>";
    }
    unset($_SESSION['errors']); // Clear errors after displaying
}

if (!empty($success_message)) {
    print "<p class='success-message'>" . htmlspecialchars($success_message) . "</p>";
}

print "<div class=\"flex-container\">";
print "<div class=\"flex-item\">";
print "<h2>Available Courses</h2>";
print $availableCoursesHTML;
print "</div>";
print "<div class=\"flex-item\">";
print "<h2>Enrolled Courses</h2>";
print $enrolledCoursesHTML;
print "</div>";
print "</div>";

print "<p><a href=\"../index.php\">Return to Main Page</a></p>";
print "</div>";

// Output Bottom Section
print $page->getBottomSection();
?>