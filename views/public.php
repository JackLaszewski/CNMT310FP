<?php
session_start(); 
require_once("../WebServiceClient.php");

//since its public user doesnt need to login
$apikey = "";
$apihash = "";


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

