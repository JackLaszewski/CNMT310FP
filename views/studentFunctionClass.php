<?php
//session_start();

require_once("../WebServiceClient.php");

class StudentFunctionClass {

    public function studentViewClasses() {

        // Check if the user is logged in as a student
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != "student") {
            $_SESSION['error_message'] = "Please login as a student.";
            header('Location: ../login.php');
            exit;
        }

        $apikey = "";
        $apihash = "";

        $url = "https://cnmt310.classconvo.com/classreg/";
        $client = new WebServiceClient($url);

        $action = "listcourses";
        $wsData = array(
            "apikey" => $apikey,
            "apihash" => $apihash,
            "action" => $action,
            "data" => array()
        );
        $client->setPostFields($wsData);
        $result = $client->send();
        $jsonResult = json_decode($result);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Result is not JSON");
        }

        if ($jsonResult->result == "Success") {
            $courses = $jsonResult->data;
            $output = "<div class='container'>";
            $output .= "<table class='course-table'>";
            $output .= "<tr><th>Course Name</th><th>Course Code</th><th>Course Number</th><th>Number of Credits</th><th>Course Description</th><th>Course Instructor</th><th>Meeting Times</th><th>Max Enrollment</th><th>Action</th></tr>";
            foreach ($courses as $course) {
                //get_object_vars returns arrays of all properites in the object
                $props = get_object_vars($course);
                $course_id = $course->id;
                $output .= "<tr>";
                foreach ($props as $key => $value) {
                    if ($key != "id" && $key != "owner_id") { // Skip id and owner_id
                        $output .= "<td>" . htmlspecialchars($value) . "</td>";
                    }
                }
                $output .= "<td><form method='POST' action='student.php?action=add_to_course'><button type='submit' name='course_id' value='" . htmlspecialchars($course_id) . "'>Add to Course</button></form></td>";
                $output .= "</tr>";
            }
            $output .= "</table>";
            $output .= "</div>";
            return $output;
        } else {
            throw new Exception("Failed to retrieve courses: " . $jsonResult->result);
        }
    }

    public function listStudentCourses($student_id) {

        $apikey = "api86";
        $apihash = "fefgwrv";

        $url = "https://cnmt310.classconvo.com/classreg/";
        $client = new WebServiceClient($url);

        $action = "getstudentcourses";
        $wsData = array(
            "apikey" => $apikey,
            "apihash" => $apihash,
            "action" => $action,
            "data" => array(
                "student_id" => $student_id
            )
        );
        $client->setPostFields($wsData);
        $result = $client->send();
        $jsonResult = json_decode($result);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Result is not JSON");
        }

        if ($jsonResult->result == "Success") {
            $courses = $jsonResult->data;
            $output = "<div class='container'>";
            $output .= "<table class='course-table'>";
            $output .= "<tr><th>Course Name</th><th>Course Code</th><th>Course Number</th><th>Number of Credits</th><th>Course Description</th><th>Course Instructor</th><th>Meeting Times</th></tr>";
            foreach ($courses as $course) {
                //returns properites of course object
                $props = get_object_vars($course);
                $output .= "<tr>";
                foreach ($props as $key => $value) { 
                    if ($key != "id" && $key != "owner_id" && $key != "student_id" && $key != "course_id") { // Skip unnecessary properties
                        $output .= "<td>" . htmlspecialchars($value) . "</td>";
                    }
                }
                $output .= "</tr>";
            }
            $output .= "</table>";
            $output .= "</div>";
            return $output;
        } else {
            throw new Exception("Failed to retrieve student courses: " . $jsonResult->result);
        }
    }
    public function addStudentToCourse($course_id, $student_id) {
        $student_id = $_SESSION['student_id'] ?? null;
        if (!$student_id) {
            throw new Exception("Student ID is missing. Please log in again.");
        }

        $apikey = "";
        $apihash = "";

        $url = "https://cnmt310.classconvo.com/classreg/";
        $client = new WebServiceClient($url);

        $action = "addstudent2course";
        $wsData = array(
            "apikey" => $apikey,
            "apihash" => $apihash,
            "action" => $action,
            "data" => array(
                "student_id" => $student_id,
                "course_id" => $course_id
            )
        );

        $client->setPostFields($wsData);
        $result = $client->send();
        $jsonResult = json_decode($result);

        // Handle errors in JSON response
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Response is not valid JSON.");
        }
        if ($jsonResult->result === "Success") {
            return "Successfully added to the course!";
        } else {
            throw new Exception("Failed to add to the course: " . htmlspecialchars($jsonResult->message ?? "Unknown error."));
        }
    }
}

?>
