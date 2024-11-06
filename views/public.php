<?php
session_start(); 
require_once("../WebServiceClient.php");


//get session variable user role to determine if its public or admin or if not logged in
//if not logged in set role to public
$role = $_SESSION['user_role'];
if(!isset($_SESSION['user_role'])){
    $role = "public";
    //if student or admin redirect to student page
}else if($role == "student" || $role == "admin"){ //do we want admin to also be able to access student page
    header("Location: student.php");
    exit();
}

//since its public user doesnt need to login
$apikey = "api36";
$apihash = "kmpcpahw";


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

print (var_dump($jsonResult));//for debugging

//if successful return list of courses
if ($jsonResult->result == "Success") {
    foreach ($jsonResult->data as $key => $value) {
        $props = get_object_vars($jsonResult->data[$key]);
        foreach ($props as $pkey => $pval) {
            print $pkey . ": " . $pval . "<br>" . PHP_EOL;
        }
    }
} else {
    print "Failed to retrieve courses";
}

//link to return to main page
print "<br>";
print "<a href=\"../index.php\">Return to Main Page</a>";
