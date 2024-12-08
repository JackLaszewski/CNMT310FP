<?php
session_start();
require_once("./studentFunctionClass.php");
//used to call get student courses from studentFunctionClass.php and act as middleman between modal and studentFunctionClass.php

//get student id from POST
$data = json_decode(file_get_contents('php://input'), true);
$_SESSION['admin_student_id'] = $data['student_id'] ?? null;

$studentFunctions = new StudentFunctionClass();

// Check if the student ID is provided
if ($_SESSION['admin_student_id']) {
    // Call the function to get the student's courses and available courses to add
    $courses = $studentFunctions->listStudentCourses($_SESSION['admin_student_id']);
    $availableCourses = $studentFunctions->adminStudentViewClasses($_SESSION['admin_student_id']);

    $courseData = array(
        'courses' => $courses,
        'availableCourses' => $availableCourses
    );

    header('Content-Type: application/json'); // Set the content type to application/json
    print json_encode($courseData);

} else {
    $_SESSION['error_message'] = "No student ID provided.";
    print $_SESSION['error_message'];
}

