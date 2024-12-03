<?php
//__DIR__ gets current directory
require_once(__DIR__ . "/../WebServiceClient.php");
require_once(__DIR__ . "/../apiConfig.php");

//get list of courses function
function getCourses(){

$apikey = API_KEY;
$apihash = API_HASH;


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

return $jsonResult;
}

function displayCourses($courses){
    if ($courses->result == "Success") {
        print "<table class='course-table'>";
        print "<tr><th>Course Name</th><th>Course Code</th><th>Course Number</th><th>Number of Credits</th><th>Course Description</th><th>Course Instructor</th><th>Meeting Times</th><th>Max Enrollment</th></tr>";
        foreach ($courses->data as $key => $value) {
            $props = get_object_vars($courses->data[$key]);
            print "<tr>";
            foreach ($props as $pkey => $pval) {
                if ($pkey != "id" && $pkey != "owner_id") { //id and owner_id are not needed and mess up table
                    print "<td>" . $pval . "</td>";
                }
            }
            print "</tr>";
        }
        print "</table>";
    } else {
        print "Failed to retrieve courses";
    }
}