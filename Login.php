<?php 

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hardcoded username and password
    // Will change later once we get how to actually do the validating
    $username = "student";
    $password = "password123";

    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Validate the login credentials
    if ($inputUsername === $username && $inputPassword === $password) {
        $_SESSION['username'] = $username; 
        header("Location: dashboard.php"); 
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
print "<link rel=\"stylesheet\" href=\"styles.css\"> <!-- Optional CSS for styling -->";
print "</head>";
print "<body>";
print "<div class=\"login-container\">";
print "<h2>Login</h2>";
if (!empty(\$error_message)) {
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
print "</body>";
print "</html>";
?>
