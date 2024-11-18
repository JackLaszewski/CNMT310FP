<?php

 $apikey = "api86";
 $apihash = "fefgwrv";

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
 )
 
 $client->setPostFields($wsData);
 $result = $client->send();
 
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


?>