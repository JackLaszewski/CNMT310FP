<?php
session_start();
require_once("../views/studentFunctionClass.php");

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== "student") {
    header("Location: ../Errors/403.php");
    exit();
}

$studentFunctions = new StudentFunctionClass();
$error_message = "";
$success_message = "";

// Initialize shopping cart if it doesn't exist
if (!isset($_SESSION['studentShoppingCart']) || !is_array($_SESSION['studentShoppingCart'])) {
    $_SESSION['studentShoppingCart'] = [];
}

// Fetch all available courses
try {
    $courses = $studentFunctions->arrayOfCourses($_SESSION['student_id']); // Fetch all course objects
    $availableCoursesHTML = $studentFunctions->studentViewClasses($_SESSION['student_id']);
} catch (Exception $e) {
    $error_message = $e->getMessage();
}

// Handle form submission (add course to shopping cart)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    // Add course to the shopping cart (if not already added)
    if (!in_array($course_id, $_SESSION['studentShoppingCart'])) {
        $_SESSION['studentShoppingCart'][] = $course_id;
    }
}

// Filter courses in the shopping cart
$shoppingCartCourses = [];
foreach ($_SESSION['studentShoppingCart'] as $course_id) {
    foreach ($courses as $course) {
        if ($course->course_id == $course_id) {
            $shoppingCartCourses[] = $course;
            break;
        }
    }
}

// Display HTML content
print "<!DOCTYPE html>";
print "<html lang=\"en\">";
print "<head>";
print "<meta charset=\"UTF-8\">";
print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
print "<title>Student View</title>";
print "<link rel=\"stylesheet\" href=\"../CSS/style.css\">";
print "</head>";
print "<body>";
print "<div class=\"container\">";
print "<h1>Student View</h1>";

if (!empty($error_message)) {
    print "<p class='error'>" . htmlspecialchars($error_message) . "</p>";
}

// Display available courses
print "<h2>Available Courses</h2>";
print $availableCoursesHTML;

// Display shopping cart
print "<h2>Shopping Cart</h2>";
if (!empty($shoppingCartCourses)) {
    print $studentFunctions->listCoursesWithCourseObject($shoppingCartCourses);
} else {
    print "<p>Your shopping cart is empty.</p>";
}

// Return to main page link
print "<p><a href=\"../index.php\">Return to Main Page</a></p>";
print "</div>";
print "</body>";
print "</html>";

//So what you could do is create a method that gives you an array of all courses instead of printing
//This method would be used to give a similar feel to uwsps add coruses. With adding courses on the left and shopping cart on the right.
//Becuase what I could do is store all the courses Id in an array because if they add multiple courses
//Then when they are done they could hit registar shopping car and it would go through and reister each course one by on.
