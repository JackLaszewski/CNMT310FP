<?php
session_start();
require_once("./studentFunctionClass.php");
//used to call get student courses from studentFunctionClass.php and act as middleman between modal and studentFunctionClass.php

//get student id from POST
$data = json_decode(file_get_contents('php://input'), true);
$studentId = $data['student_id'] ?? null;


// Check if the student ID is provided
if ($studentId) {
    // Call the function to get the student's courses
    $studentFunctions = new StudentFunctionClass();
    $courses = $studentFunctions->listStudentCourses($studentId);

    header('Content-Type: text/html'); // Set the content type to HTML
    print $courses;

} else {
    $_SESSION['error_message'] += "No student ID provided.";
    print $_SESSION['error_message'];
}

