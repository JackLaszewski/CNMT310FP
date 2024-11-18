<?php
session_start(); 
require_once("../WebServiceClient.php");

$apikey = "api86";
$apihash = "fefgwrv";

$student_id = $_SESSION['student_id'] ?? null; 
$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    if ($student_id) {
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

        if (json_last_error() !== JSON_ERROR_NONE) {
            $error_message = "Response is not valid JSON.";
        } elseif ($jsonResult->result == "Success") {
            $success_message = "Successfully added to the course!";
        } else {
            $error_message = "Failed to add to the course: " . htmlspecialchars($jsonResult->message ?? "Unknown error.");
        }
    } else {
        $error_message = "Student ID is missing. Please log in again.";
    }
}

print "<!DOCTYPE html>";
print "<html lang=\"en\">";
print "<head>";
print "<meta charset=\"UTF-8\">";
print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
print "<title>Student View</title>";
print "<link rel=\"stylesheet\" href=\"../CSS/style.css\">";
print "</head>";
print "<body>";
print "<div class=\"container\">";
print "<h1>Student View - Add Course</h1>";

if ($error_message) {
    print "<p class='error-message'>" . htmlspecialchars($error_message) . "</p>";
}
if ($success_message) {
    print "<p class='success-message'>" . htmlspecialchars($success_message) . "</p>";
}

print "<form method=\"POST\" action=\"studentView.php\">";
print "<label for=\"course_id\">Course ID:</label>";
print "<input type=\"text\" id=\"course_id\" name=\"course_id\" required>";
print "<button type=\"submit\">Add to Course</button>";
print "</form>";

print "<p><a href=\"../index.php\">Return to Main Page</a></p>";

print "</div>";
print "</body>";
print "</html>";
?>