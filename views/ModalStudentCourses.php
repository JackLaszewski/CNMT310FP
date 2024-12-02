<?php
require_once("./studentFunctionClass.php");
//used to call get student courses from studentFunctionClass.php and act ass middleman between modal and studentFunctionClass.php

print("test");

//get student id from POST
$data = json_decode(file_get_contents('php://input'), true);
$studentId = $data['student_id'] ?? null;

print("received student id: " . $studentId);

// Check if the student ID is provided
if ($studentId) {
    // Call the function to get the student's courses
    $studentFunctions = new StudentFunctionClass();
    $courses = $studentFunctions->listStudentCourses($studentId); 

    // Return the courses in JSON format
    print json_encode(['courses' => $courses]);
} else {
    print json_encode(['error' => 'No student ID provided']);
}