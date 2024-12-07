<?php
session_start();
require_once("../views/studentFunctionClass.php");

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

// Handle adding a course
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    try {
        $success_message = $studentFunctions->addStudentToCourse($student_id, $course_id);
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

try {
    // Pass $student_id to both functions
    $availableCoursesHTML = $studentFunctions->studentViewClasses($student_id);
    $enrolledCoursesHTML = $studentFunctions->listStudentCourses($student_id);
} catch (Exception $e) {
    $error_message = $e->getMessage();
}

print "<!DOCTYPE html>";
print "<html lang=\"en\">";
print "<head>";
print "<meta charset=\"UTF-8\">";
print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
print "<title>Student View</title>";
print "<link rel=\"stylesheet\" href=\"../CSS/student.css\">"; // Make sure this file is updated
print "</head>";
print "<body>";
print "<div class=\"container\">";
print "<h1>Student View</h1>";

if ($error_message) {
    print "<p class='error-message'>" . htmlspecialchars($error_message) . "</p>";
}
if ($success_message) {
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
print "</body>";
print "</html>";
?>