<?php
session_start(); 

require_once("../WebServiceClient.php");

class StudentFunctionClass {
    public function studentViewClasses() {
    
        $apikey = "";
        $apihash = "";
    
        // Set up the web service client
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
            $output .= "<tr><th>Course Name</th><th>Course Code</th><th>Course Number</th><th>Number of Credits</th><th>Course Description</th><th>Course Instructor</th><th>Meeting Times</th><th>Max Enrollment</th></tr>";
            foreach ($courses as $course) {
                $props = get_object_vars($course);
                $output .= "<tr>";
                foreach ($props as $key => $value) {
                    if ($key != "id" && $key != "owner_id") { // Skip id and owner_id
                        $output .= "<td>" . htmlspecialchars($value) . "</td>";
                    }
                }
                $output .= "</tr>";
            }
            $output .= "</table>";
            $output .= "</div>";
            return $output;
        } else {
            throw new Exception("Failed to retrieve courses: " . $jsonResult->result);
        }
    }
    public function studentAddCourse(){
    
        $apikey = "";
        $apihash = "";
    
        // Set up the web service client
        $url = "https://cnmt310.classconvo.com/classreg/";
        $client = new WebServiceClient($url);
    
        $action = "addstudent2course";
        $wsData = array(
            "apikey" => $apikey,
            "apihash" => $apihash,
            "action" => $action,
            "data" => array()
        );
        $client->setPostFields($wsData);
    
        $result = $client->send();
        $jsonResult = json_decode($result);
    }
}   
?>
