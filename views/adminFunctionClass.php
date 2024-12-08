<?php
session_start();

require_once("../WebServiceClient.php");
require_once("./studentFunctionClass.php");
require_once("../apiConfig.php");


class AdminFunctionClass
{

    public function addClassApiCall(): void
{
    $expected = [
        "coursename" => "Please provide the course name.",
        "coursecode" => "Please provide the course code.",
        "coursenum" => "Please provide the course number.",
        "coursecredits" => "Please provide the number of credits.",
        "coursedesc" => "Please provide the course description.",
        "courseinstr" => "Please provide the course instructor.",
        "meetingtimes" => "Please provide the meeting times.",
        "maxenroll" => "Please provide the maximum enrollment."
    ];

    $errors = [];
    foreach ($expected as $key => $message) {
        if (empty($_POST[$key])) {
            $errors[] = $message;
        }
    }

    if (!empty($errors)) {
        echo json_encode([
            "result" => "Error",
            "message" => "Validation errors",
            "errors" => $errors
        ]);
        return;
    }

    $apikey = API_KEY;
    $apihash = API_HASH;

    $data = [
        "coursename" => $_POST['coursename'],
        "coursecode" => $_POST['coursecode'],
        "coursenum" => $_POST['coursenum'],
        "coursecredits" => $_POST['coursecredits'],
        "coursedesc" => $_POST['coursedesc'],
        "courseinstr" => $_POST['courseinstr'],
        "meetingtimes" => $_POST['meetingtimes'],
        "maxenroll" => $_POST['maxenroll']
    ];

    $url = "https://cnmt310.classconvo.com/classreg/";
    $client = new WebServiceClient($url);

    $fields = [
        "apikey" => $apikey,
        "apihash" => $apihash,
        "action" => "addcourse",
        "data" => $data
    ];

    $client->setPostFields($fields);
    $result = $client->send();

    $jsonResult = json_decode($result);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode([
            "result" => "Error",
            "message" => "Invalid server response"
        ]);
        return;
    }

    if ($jsonResult->result === "Success") {
        echo json_encode([
            "result" => "Success",
            "data" => $jsonResult->data
        ]);
    } else {
        echo json_encode([
            "result" => "Error",
            "message" => $jsonResult->message ?? "Unknown error"
        ]);
    }
}

public function addClassTemplateView()
{
    $output = ""; // Initialize output variable

    $output .= "<form id=\"add-class-form\" method=\"POST\">";
    $output .= "<label for=\"coursename\">Course name:</label>";
    $output .= "<input type=\"text\" id=\"coursename\" name=\"coursename\" required><br>";
    $output .= "<label for=\"coursecode\">Course Code:</label>";
    $output .= "<input type=\"text\" id=\"coursecode\" name=\"coursecode\" required><br>";
    $output .= "<label for=\"coursenum\">Course Number:</label>";
    $output .= "<input type=\"text\" id=\"coursenum\" name=\"coursenum\" required><br>";
    $output .= "<label for=\"coursecredits\">Number of Credits:</label>";
    $output .= "<input type=\"text\" id=\"coursecredits\" name=\"coursecredits\" required><br>";
    $output .= "<label for=\"coursedesc\">Course Description:</label>";
    $output .= "<input type=\"text\" id=\"coursedesc\" name=\"coursedesc\" required><br>";
    $output .= "<label for=\"courseinstr\">Course Instructor:</label>";
    $output .= "<input type=\"text\" id=\"courseinstr\" name=\"courseinstr\" required><br>";
    $output .= "<label for=\"meetingtimes\">Meeting Times:</label>";
    $output .= "<input type=\"text\" id=\"meetingtimes\" name=\"meetingtimes\" required><br>";
    $output .= "<label for=\"maxenroll\">Max Enroll:</label>";
    $output .= "<input type=\"text\" id=\"maxenroll\" name=\"maxenroll\" required><br>";
    $output .= "<button type=\"button\" id=\"submit-add-class\">Add Class</button>";
    $output .= "</form>";

    $output .= "<a href=\"adminDashboard.php\">Admin Dashboard</a>";

    return $output; // Return the output for rendering
}

    // This will display all classes 
    public function manageClassesView()
{
    $output = ""; // Initialize output variable

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
        return "Result is not JSON";
    }

    if ($jsonResult->result == "Success") {
        $output .= "<h2>Manage Classes</h2>";
        $output .= "<form method=\"POST\" action=\"adminFunctionView.php?action=delete_class\">";
        $output .= "<table class='course-table'>";
        $output .= "<tr><th>Course Name</th><th>Course Code</th><th>Course Number</th><th>Number of Credits</th><th>Course Description</th><th>Course Instructor</th><th>Meeting Times</th><th>Max Enrollment</th><th>Action</th></tr>";

        foreach ($jsonResult->data as $key => $value) {
            $props = get_object_vars($jsonResult->data[$key]);
            $course_id = $jsonResult->data[$key]->id;
            $output .= "<tr>";
            foreach ($props as $pkey => $pval) {
                if ($pkey != "id" && $pkey != "owner_id") {
                    $output .= "<td>" . htmlspecialchars($pval) . "</td>";
                }
            }
            $output .= "<td><button type=\"submit\" name=\"course_id\" value=\"$course_id\">Delete</button></td>";
            $output .= "</tr>";
        }
        $output .= "</table>";
        $output .= "</form>";
    } else {
        $output .= "<p>Failed to retrieve courses</p>";
    }

    $output .= "<br>";
    $output .= "<a href=\"../index.php\">Return to Main Page</a><br>";
    $output .= "<a href=\"adminDashboard.php\">Admin Dashboard</a>";

    return $output;
}
public function deleteClassApiCall($course_id)
{
    $output = ""; // Initialize output variable

    $apikey = API_KEY;
    $apihash = API_HASH;

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
        return "Result is not JSON";
    }

    if ($jsonResult->result === "Success") {
        $output .= "<p>Course deleted successfully. Number of courses deleted: " . htmlspecialchars($jsonResult->data->course_deleted) . "</p>";
        $output .= "<p>Number of student enrollments deleted: " . htmlspecialchars($jsonResult->data->studentenrollmentsdeleted) . "</p>";
    } else {
        $output .= "<p>Failed to delete the course. Error: " . htmlspecialchars($jsonResult->result) . "</p>";
    }

    return $output;
}

public function manageStudents()
{
    $output = ""; // Initialize output variable

    $apikey = API_KEY;
    $apihash = API_HASH;

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
        return "Result is not JSON"; // Return error as a string
    }

    if ($jsonResult->result == "Success") {
        $output .= "<h2>Manage Students</h2>";
        $output .= "<table class='student-table'>";
        $output .= "<tr><th>Student ID</th><th>Student Username</th><th>Student Name</th><th>Student Email</th><th>View Student Courses</th></tr>";

        foreach ($jsonResult->data as $key => $value) {
            $props = get_object_vars($jsonResult->data[$key]);
            $studentId = $jsonResult->data[$key]->id;
            $output .= "<tr>";
            foreach ($props as $pkey => $pval) {
                if ($pkey != "user_role") {
                    $output .= "<td>" . htmlspecialchars($pval) . "</td>";
                }
            }
            $output .= "<td><button class=\"view-course\" data-student-id=\"" . $studentId . "\">View Courses</button></td>";
            $output .= "</tr>";
        }
        $output .= "</table>";
    } else {
        $output .= "<p>Failed to retrieve students</p>";
    }

    // Add modal popup for viewing courses
    $output .= "<div id=\"modal\" class=\"modal\">";
    $output .= "<div class=\"modal-content\">";
    $output .= "<span class=\"close\">&times;</span>";
    $output .= "<h2>Student Courses</h2>";
    $output .= "<div class=\"flex-container\">";
    $output .= "<div class=\"flex-item\" id=\"modal-student-course-list\"></div>";
    $output .= "<h2>Available Courses</h2>";
    $output .= "<div class=\"flex-item\" id=\"modal-available-course-list\"></div>";
    $output .= "</div>";
    $output .= "</div>";
    $output .= "</div>";

    $output .= "<br>";
    $output .= "<a href=\"../index.php\">Return to Main Page</a>";
    $output .= "<br>";
    $output .= "<a href=\"adminDashboard.php\">Admin Dashboard</a>";

    return $output; // Return the generated output
}
}
?>