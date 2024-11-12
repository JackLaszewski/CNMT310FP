<?php
session_start();
require_once("../WebServiceClient.php");

class StudentFunctionClass {
    private $client;
    private $apikey = "api36";
    private $apihash = "kmpcpahw";
    
    public function __construct() {
        // Set up the web service client
        $url = "https://cnmt310.classconvo.com/classreg/";
        $this->client = new WebServiceClient($url);
    }

    public function validateStudentRole() {
        // Ensure user is logged in as student
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != "student") {
            $_SESSION['error_message'] = "Please login as a student.";
            exit(header('Location: ../login.php'));
        }
    }

    public function listCoursesView() {
        $this->validateStudentRole();
        
        $action = "listcourses";
        $wsData = array(
            "apikey" => $this->apikey,
            "apihash" => $this->apihash,
            "action" => $action,
            "data" => array()
        );
        
        $this->client->setPostFields($wsData);
        $result = $this->client->send();
        $jsonResult = json_decode($result);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            print "Result is not JSON";
            exit;
        }
        
        // Print the list of courses
        if ($jsonResult->result == "Success") {
            print "<table class='course-table'>";
            print "<tr><th>Course Name</th><th>Course Code</th><th>Course Number</th><th>Number of Credits</th><th>Course Description</th><th>Course Instructor</th><th>Meeting Times</th><th>Max Enrollment</th></tr>";
            foreach ($jsonResult->data as $key => $value) {
                $props = get_object_vars($jsonResult->data[$key]);
                print "<tr>";
                foreach ($props as $pkey => $pval) {
                    if ($pkey != "id" && $pkey != "owner_id") { 
                        print "<td>" . htmlspecialchars($pval) . "</td>";
                    }
                }
                print "</tr>";
            }
            print "</table>";
        } else {
            print "Failed to retrieve courses";
        }
    }
}
?>
