<?php
//session_start();

require_once("../WebServiceClient.php");
require_once("../apiConfig.php");

class StudentFunctionClass
{

    public function studentViewClasses($student_id)
    {
        if (!$student_id) {
            throw new Exception("Student ID is missing. Please log in again.");
        }

        $apikey = API_KEY;
        $apihash = API_HASH;

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
            $output = "<div class='table-container'>"; /* Renamed container for tables */
            $output .= "<table class='course-table'>";
            $output .= "<tr><th>Course Name</th><th>Course Code</th><th>Course Number</th><th>Number of Credits</th><th>Course Description</th><th>Course Instructor</th><th>Meeting Times</th><th>Max Enrollment</th><th>Action</th></tr>";
            foreach ($courses as $course) {
                $props = get_object_vars($course);
                $course_id = $course->id;
                $output .= "<tr>";
                foreach ($props as $key => $value) {
                    if ($key != "id" && $key != "owner_id") { // Skip id and owner_id
                        $output .= "<td>" . htmlspecialchars($value) . "</td>";
                    }
                }
                $output .= "<td>";
                $output .= "<form method='POST' action='studentView.php'>";
                $output .= "<input type='hidden' name='course_id' value='" . htmlspecialchars($course_id) . "'>";
                $output .= "<button type='submit'>Add to Course</button>";
                $output .= "</form>";
                $output .= "</td>";
                $output .= "</tr>";
            }
            $output .= "</table>";
            $output .= "</div>";
            return $output;
        } else {
            throw new Exception("Failed to retrieve courses: " . $jsonResult->result);
        }
    }
    public function listStudentCourses($student_id)
    {

        $apikey = API_KEY;
        $apihash = API_HASH;

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
            $output = "<div class='table-container'>"; /* Renamed container for tables */
            $output .= "<table class='course-table'>";
            $output .= "<tr><th>Course Name</th><th>Course Code</th><th>Course Number</th><th>Number of Credits</th><th>Course Description</th><th>Course Instructor</th><th>Meeting Times</th><th>Max Enrollment</th></tr>";
            foreach ($courses as $course) {
                //returns properites of course object
                $props = get_object_vars($course);
                $course_id = $course->id;
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
    public function addStudentToCourse($student_id, $course_id)
    {
        if (!$student_id) {
            throw new Exception("Student ID is missing. Please log in again.");
        }

        $apikey = API_KEY;
        $apihash = API_HASH;

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
            throw new Exception("Response is not valid JSON. Raw response: " . $result);
        }
        
        if ($jsonResult->result === "Success") {
            return "Successfully added to the course!";
        } else {
            $errorMessage = htmlspecialchars($jsonResult->message ?? "Unknown error.");
            throw new Exception("Failed to add to the course: " . $errorMessage);
        }
    }

    public function removeStudentFromCourse($student_id, $course_id)
    {
        if (!$student_id) {
            throw new Exception("Student ID is missing. Please log in again.");
        }

        $apikey = API_KEY;
        $apihash = API_HASH;

        $url = "https://cnmt310.classconvo.com/classreg/";
        $client = new WebServiceClient($url);

        $action = "delstudentfromcourse";
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
            throw new Exception("Response is not valid JSON. Raw response: " . $result);
        }
        if ($jsonResult->result === "Success") {
            return "Successfully removed from the course!";
        } else {
            $errorMessage = htmlspecialchars($jsonResult->message ?? "Unknown error.");
            throw new Exception("Failed to remove from the course: " . $errorMessage);
        }
    }


    //put in here because the tables in the modal broke when this was in adminfunctionclass
    //not sure why
    //needed to change the action of the form submission 
    public function adminStudentViewClasses($student_id)
    {
        if (!$student_id) {
            throw new Exception("Student ID is missing. Please log in again.");
        }

        $apikey = API_KEY;
        $apihash = API_HASH;

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
            $output = "<div class='table-container'>"; /* Renamed container for tables */
            $output .= "<table class='course-table'>";
            $output .= "<tr><th>Course Name</th><th>Course Code</th><th>Course Number</th><th>Number of Credits</th><th>Course Description</th><th>Course Instructor</th><th>Meeting Times</th><th>Max Enrollment</th><th>Action</th></tr>";
            foreach ($courses as $course) {
                $props = get_object_vars($course);
                $course_id = $course->id;
                $output .= "<tr>";
                foreach ($props as $key => $value) {
                    if ($key != "id" && $key != "owner_id") { // Skip id and owner_id
                        $output .= "<td>" . htmlspecialchars($value) . "</td>";
                    }
                }
                $output .= "<td>";
                $output .= "<form method='POST' action='adminFunctionView.php'>";
                $output .= "<input type='hidden' name='add_student_course' value='" . htmlspecialchars($course_id) . "'>";
                $output .= "<button type='submit'>Add to Course</button>";
                $output .= "</form>";
                $output .= "</td>";
                $output .= "</tr>";
            }
            $output .= "</table>";
            $output .= "</div>";
            return $output;
        } else {
            throw new Exception("Failed to retrieve courses: " . $jsonResult->result);
        }
    }
}
?>