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
$errors = [];

// Check if form was submitted by verifying if 'course_id' exists in $_POST
if (isset($_POST['course_id'])) {
    $required = ["course_id"];

    // Validate the POST data
    foreach ($required as $req) {
        if (empty($_POST[$req])) {
            $errors[] = "Please select a course.";
        }
    }

    // Process form if no errors
    if (empty($errors)) {
        try {
            $course_id = $_POST['course_id'];
            $success_message = $studentFunctions->addStudentToCourse($student_id, $course_id);
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
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

// Display errors or success messages
if (!empty($errors)) {
    foreach ($errors as $error) {
        print "<p class='error-message'>" . htmlspecialchars($error) . "</p>";
    }
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