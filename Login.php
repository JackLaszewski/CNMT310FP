<?php
session_start(); 
require_once("WebServiceClient.php");

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $apikey = "";
    $apihash = "";

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
        $error_message = "Invalid Username or Password!";
    }
}

print "<!DOCTYPE html>";
print "<html lang=\"en\">";
print "<head>";
print "<meta charset=\"UTF-8\">";
print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
print "<title>Login Page</title>";
print "<link rel=\"stylesheet\" href=\"CSS/style.css\">";
print "</head>";
print "<body>";
print "<div class=\"login-container\">";
print "<h2>Login</h2>";
if (!empty($error_message)) {
    print "<p class='error-message'>\$error_message</p>";
}
print "<form method=\"POST\" action=\"login.php\">";
print "<label for=\"username\">Username:</label>";
print "<input type=\"text\" id=\"username\" name=\"username\" required>";
print "<label for=\"password\">Password:</label>";
print "<input type=\"password\" id=\"password\" name=\"password\" required>";
print "<button type=\"submit\">Login</button>";
print "</form>";
print "</div>";

print "<p><a href=\"index.php\">Return to Main Page</a></p>";

print "</body>";
print "</html>";
?>