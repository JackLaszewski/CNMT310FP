<?php
session_start();
require_once("views/courseTableFunctions.php");
unset($_SESSION['error_message']);//clears error message when user returns to landing page

print "<!doctype html>";
print "<html lang=\"en\">";
print "<head>";
print "<title>Landing Page</title>";
print "<link rel=\"stylesheet\" type=\"text/css\" href=\"CSS/style.css\">";
print "<script src=\"JS/indexPage.js\"></script>";
print "</head>";
print "<body>";

print "<header>";
print "<h1>Welcome to the landing page!</h1>";
print "</header>";
print "<nav>";
print "<ul>";
print "<li><a href=\"index.php\">Home</a></li>";

// Check if user is not logged in
if (!isset($_SESSION['user_role'])) {
    print "<li><a href=\"views/public.php\">Classes</a></li>";
    print "<li><a id =\"login-button\" href=\"login.php\">Login</a></li>";

} else {
    // Check if user is an admin and show the admin dashboard link
    if ($_SESSION['user_role'] == 'admin') {
        print "<li><a href=\"views/adminDashboard.php\">Admin Dashboard</a></li>";
    } elseif ($_SESSION['user_role'] == 'student') {
        print "<li><a href=\"views/student.php\">Classes</a></li>";
    }
    print "<li><a id =\"logout-button\" href=\"logout.php\">Logout</a></li>";
}
print "</ul>";
print "</nav>";


print "<div class=\"card-container\">";
print "<div class=\"class-card\">";
print "<h3>Card 1</h3>";
print "<p>This is a card.</p>";

//testing functions
$courses = getCourses();
displayCourses($courses);
print "</div>";



//NEED TO MAKE LOGIN CLASS TO CLEAN THIS UP
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $apikey = "api36";
    $apihash = "kmpcpahw";

    // Get user input from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Set up the web service client
    $url = "https://cnmt310.classconvo.com/classreg/";
    $client = new WebServiceClient($url);

    $action = "authenticate";
    $data = array("username" => $username, "password" => $password);
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

    // Handle the result of authentication
    if ($jsonResult->result == "Success") {
        $_SESSION['username'] = $username;
        $_SESSION['user_role'] = $jsonResult->data->user_role; //can use this to check if user is loggen in and what role they have
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Invalid Username or Password!";
    }
}

print "<div class=\"login-card\">";
print "<h3>Card 2</h3>";
print "<p>This is another card.</p>";

print "<div class=\"login-container\">";
print "<h2>Login</h2>";
if (!empty($_SESSION['error_message'])) {
    print "<p class='error-message'>{$_SESSION['error_message']}</p>";
}
print "<form id=\"login-form\" method=\"POST\" >";
print "<label for=\"username\">Username:</label>";
print "<input type=\"text\" id=\"username\" name=\"username\" required>";
print "<label for=\"password\">Password:</label>";
print "<input type=\"password\" id=\"password\" name=\"password\" required>";
print "<button id=\"login-button\" type=\"submit\">Login</button>";
print "</form>";
print "</div>";


print "</div>";
print "</div>";


print "</body>";
print "</html>";
?>