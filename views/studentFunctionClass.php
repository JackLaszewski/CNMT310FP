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
            $output = "<div class='container'>";
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
                $output .= "<td>
                            <form method='POST' action='studentAdd.php'>
                                <input type='hidden' name='course_id' value='" . htmlspecialchars($course_id) . "'>
                                <button type='submit'>Add to Course</button>
                            </form>
                            </td>";
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
            $output = "<div class='container'>";
            $output .= "<table class='course-table'>";
            $output .= "<tr><th>Course Name</th><th>Course Code</th><th>Course Number</th><th>Number of Credits</th><th>Course Description</th><th>Course Instructor</th><th>Meeting Times</th><th>Max Enrollment</th><th>Action</th></tr>";
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
                $output .= "<td>
                            <form method='POST' action=''>
                                <button type='submit' name='drop_course' value='" . $student_id . "," . $course_id . "'>Drop Course</button>
                            </form>
                            </td>";
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


    public function arrayOfCourses($student_id)
    {

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

            return $courses;
        } else {
            throw new Exception("Failed to retrieve student courses: " . $jsonResult->result);
        }
    }
    public function listCoursesWithCourseObject($courseObj)
    {
        if (!is_array($courseObj)) {
            $props = get_object_vars($courseObj);
            foreach ($props as $key => $value) {
                if ($key != "id" && $key != "owner_id" && $key != "student_id" && $key != "course_id") { // Skip unnecessary properties
                    $output .= "<td>" . htmlspecialchars($value) . "</td>";
                }
            }
        } else {
            $output = "<div class='container'>";
            $output .= "<table class='course-table'>";
            $output .= "<tr><th>Course Name</th><th>Course Code</th><th>Course Number</th><th>Number of Credits</th><th>Course Description</th><th>Course Instructor</th><th>Meeting Times</th></tr>";
            foreach ($courseObj as $course) {
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
        }
    }
}


?>
//So what you could do is create a method that gives you an array of all courses instead of printing
//This method would be used to give a similar feel to uwsps add coruses. With adding courses on the left and shopping
cart on the right.
//Becuase what I could do is store all the courses Id in an array because if they add multiple courses
//Then when they are done they could hit registar shopping car and it would go through and reister each course one by
on.