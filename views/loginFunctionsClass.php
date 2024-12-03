<?php
function Login($username, $password)
{
    $apikey = "api86";
    $apihash = "fefgwrv";
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

    if ($jsonResult->result == "Success") {
        $_SESSION['username'] = $username;
        $_SESSION['user_role'] = $jsonResult->data->user_role; //can use this to check if user is loggen in and what role they have
        $_SESSION['student_id'] = $jsonResult->data->id;
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Invalid Username or Password!";
    }

    //check isset/empty on each one
    $expected = array(array('username' => 'please fill in username'), array('password' => 'please fill in password'));
    $error = false;
    foreach ($expected as $key => $value) {
        if (!isset($_POST[$key]) || empty($_POST[$key])) {
            $_SESSION['error_message'] = $expected[$key];
            $error = true;
        }
    }
    if ($error === true) {
        die(header("Location: index.php"));
    }

}
