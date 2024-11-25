<?php
session_start();

require_once("../WebServiceClient.php");
require_once("./studentFunctionClass.php");


class AdminFunctionClass
{

    public function addClassApiCall()
    {
        //We want to get rid of Request_Method and use $_Post for security reasons 
        //$exoected = array("coursename", "coursecode","coursenum","courseinstructor", ect.)

        //foreach )$expected as $value)
        //if(!isset($_POST($value)|| empty($_POST($vlaue))))
        //$_SESSION('errors')[] = "Please cmoplete all fields
        //die(header("location: /index.php))
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $apikey = "api86";
            $apihash = "fefgwrv";

            // Get user input from form
            $coursename = $_POST['coursename'];
            $coursecode = $_POST['coursecode'];
            $coursenum = $_POST['coursenum'];
            $coursecredits = $_POST['coursecredits'];
            $coursedesc = $_POST['coursedesc'];
            $courseinstr = $_POST['courseinstr'];
            $meetingtimes = $_POST['meetingtimes'];
            $maxenroll = $_POST['maxenroll'];

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

            $client->setPostFields($fields);
            $result = $client->send();

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

    public function addClassTemplateView()
    {
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

        print "<a href=\"adminDashboard.php\">Admin Dashboard</a>";

        // After form submission, call the API method
        $this->addClassApiCall();
    }

    // This will display all classes 
    public function manageClassesView()
    {
        $apikey = "api86";
        $apihash = "fefgwrv";

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
            print "Result is not JSON";
            exit;
        }

        // Print the course list in a table
        if ($jsonResult->result == "Success") {
            print "<h2>Manage Classes</h2>";
            print "<form method=\"POST\" action=\"adminFunctionView.php?action=delete_class\">";
            print "<table class='course-table'>";
            print "<tr><th>Course Name</th><th>Course Code</th><th>Course Number</th><th>Number of Credits</th><th>Course Description</th><th>Course Instructor</th><th>Meeting Times</th><th>Max Enrollment</th><th>Action</th></tr>";

            foreach ($jsonResult->data as $key => $value) {
                $props = get_object_vars($jsonResult->data[$key]);
                $course_id = $jsonResult->data[$key]->id;
                print "<tr>";
                foreach ($props as $pkey => $pval) {
                    if ($pkey != "id" && $pkey != "owner_id") {
                        print "<td>" . htmlspecialchars($pval) . "</td>";
                    }
                }
                //Here we set the value of course id so deleting it, is super easy
                print "<td><button type=\"submit\" name=\"course_id\" value=\"$course_id\">Delete</button></td>";
                print "</tr>";
            }
            print "</table>";
            print "</form>";
        } else {
            print "Failed to retrieve courses";
        }

        print "<br>";
        print "<a href=\"../index.php\">Return to Main Page</a>";
        print "<br>";
        print "<a href=\"adminDashboard.php\">Admin Dashboard</a>";
    }
    public function deleteClassApiCall($course_id)
    {
        $apikey = "api86";
        $apihash = "fefgwrv";

        $url = "https://cnmt310.classconvo.com/classreg/";
        $client = new WebServiceClient($url);

        $action = "deletecourse";
        $wsData = array(
            "apikey" => $apikey,
            "apihash" => $apihash,
            "action" => $action,
            "data" => array(
                "course_id" => $course_id
            )
        );
        $client->setPostFields($wsData);

        $result = $client->send();
        $jsonResult = json_decode($result);

        if (json_last_error() !== JSON_ERROR_NONE) {
            print "Result is not JSON";
            exit;
        }

        // Handle result of deleting a class
        if ($jsonResult->result == "Success") {
            print "<p>Course deleted successfully. Number of courses deleted: " . $jsonResult->data->course_deleted . "</p>";
            print "<p>Number of student enrollments deleted: " . $jsonResult->data->studentenrollmentsdeleted . "</p>";
        } else {
            print "<p>Failed to delete the course. Error: " . $jsonResult->result . "</p>";
        }
    }

    public function manageStudents()
    {
        $apikey = "api86";
        $apihash = "fefgwrv";

        $url = "https://cnmt310.classconvo.com/classreg/";
        $client = new WebServiceClient($url);

        $action = "liststudents";
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
            print "Result is not JSON";
            exit;
        }

        if ($jsonResult->result == "Success") {
            print "<h2>Manage Students</h2>";
            print "<table class='student-table'>";
            print "<tr><th>Student ID</th><th>Student Username</th><th>Student Name</th><th>Student Email</th><th>View Student Courses</th></tr>";

            foreach ($jsonResult->data as $key => $value) {
                $props = get_object_vars($jsonResult->data[$key]);
                $studentId = $jsonResult->data[$key]->id;
                print "<tr>";
                foreach ($props as $pkey => $pval) {
                    if ($pkey != "user_role") {
                        print "<td>" . htmlspecialchars($pval) . "</td>";
                    }
                }
                print "<td><button id=\"view-course\" type=\"submit\" name=\"user_id\"" . $studentId . "\">View Courses</button></td>";
                print "</tr>";
            }
            print "</table>";
        } else {
            print "Failed to retrieve students";
        }
        //modal popup
        print "<div id=\"modal\" class=\"modal\">";
        print "<div class=\"modal-content\">";
        print "<span class=\"close\">&times;</span>";
        print "<h2>Students Courses</h2>";
        print "<p>Modal content goes here.</p>";
        $studentFunctions = new StudentFunctionClass();
        $studentId = $_POST['user_id'] ?? null; //sets the student id to null if not set or empty
        if ($studentId) {
            $studentFunctions->listStudentCourses($studentId);
        } else {
            print "<p>No student ID provided.</p>";
        }

        print "</div>";
        print "</div>";

        print "<br>";
        print "<a href=\"../index.php\">Return to Main Page</a>";
        print "<br>";
        print "<a href=\"adminDashboard.php\">Admin Dashboard</a>";

    }
}
?>