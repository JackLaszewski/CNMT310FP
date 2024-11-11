<?php
session_start(); 

require_once("WebServiceClient.php");

class AdminFunctionClass {
    public function addClassApiCall() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $apikey = "";
            $apihash = "";
            
            // Get user input from form
            $coursename = $_POST['coursename'];
            $coursecode = $_POST['coursecode'];
            $coursenum = $_POST['coursenum'];
            $coursecredits = $_POST['coursecredits'];
            $coursedesc = $_POST['coursedesc'];
            $courseinstr = $_POST['courseinstr'];
            $meetingtimes = $_POST['meetingtimes'];
            $maxenroll = $_POST['maxenroll'];
        
            // Set up the web service client
            $url = "https://cnmt310.classconvo.com/classreg/";
            $client = new WebServiceClient($url);
        
            $action = "addcourse";
            $data = array(
                "coursename" => $coursename, 
                "coursecode" => $coursecode,
                "coursenum" => $coursenum,
                "coursecredits" => $coursecredits,
                "coursedesc" => $coursedesc,
                "courseinstr" => $courseinstr,
                "meetingtimes" => $meetingtimes,
                "maxenroll" => $maxenroll
            );
            $fields = array(
                "apikey" => $apikey,
                "apihash" => $apihash,
                "action" => $action,
                "data" => $data
            );
        
            // Set fields and send the request
            $client->setPostFields($fields);
            $result = $client->send();
        
            // Decode JSON response
            $jsonResult = json_decode($result);
            if (json_last_error() !== JSON_ERROR_NONE) {
                print "Result is not JSON";
                exit;
            }
        
            // Handle Result of adding a class
            if ($jsonResult->result == "Success") {
                $course_id = $jsonResult->data->course_id;
                print "<h1>Course Added Successfully</h1>";
                print "<p>Course ID: " . htmlspecialchars($course_id) . "</p>";
            } else {
                $error_message = "Error adding the course!";
                print "<p class='error-message'>" . htmlspecialchars($error_message) . "</p>";
            }
        }
    }

    public function addClassTemplateView() {
        print "<form method=\"POST\" action=\"adminFunctionView.php?action=add_class\">";
        print "<label for=\"coursename\">Course name:</label>";
        print "<input type=\"text\" id=\"coursename\" name=\"coursename\" required><br>";
        print "<label for=\"coursecode\">Course Code:</label>";
        print "<input type=\"text\" id=\"coursecode\" name=\"coursecode\" required><br>";
        print "<label for=\"coursenum\">Course Number:</label>";
        print "<input type=\"text\" id=\"coursenum\" name=\"coursenum\" required><br>";
        print "<label for=\"coursecredits\">Number of Credits:</label>";
        print "<input type=\"text\" id=\"coursecredits\" name=\"coursecredits\" required><br>";
        print "<label for=\"coursedesc\">Course Description:</label>";
        print "<input type=\"text\" id=\"coursedesc\" name=\"coursedesc\" required><br>";
        print "<label for=\"courseinstr\">Course Instructor:</label>";
        print "<input type=\"text\" id=\"courseinstr\" name=\"courseinstr\" required><br>";
        print "<label for=\"meetingtimes\">Meeting Times:</label>";
        print "<input type=\"text\" id=\"meetingtimes\" name=\"meetingtimes\" required><br>";
        print "<label for=\"maxenroll\">Max Enroll:</label>";
        print "<input type=\"text\" id=\"maxenroll\" name=\"maxenroll\" required><br>";
        print "<button type=\"submit\">Add Class</button>";
        print "</form>";

        // After form submission, call the API method
        $this->addClassApiCall();
    }

    // Placeholder functions for manageClasses and manageStudents
    public function manageClasses() {
        print "<p>Manage classes functionality is under development.</p>";
    }

    public function manageStudents() {
        print "<p>Manage students functionality is under development.</p>";
    }
}
?>
