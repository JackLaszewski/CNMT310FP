<?php

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

//page header
print "<header>";
print "<h1>Student Page</h1>";
print "</header>";
//nav bar
print "<nav>";
print "<ul>";
print "<li><a href=\"../index.php\">Home</a></li>";
print "<li><a href=\"public.php\">Classes</a></li>";
print "<li><a href=\"../login.php\">Login</a></li>";
print "</ul>";
print "</nav>";

//if successful print list of courses
if ($jsonResult->result == "Success") {
    print "<table class='course-table'>";
    print "<tr><th>Course Name</th><th>Course Code</th><th>Course Number</th><th>Number of Credits</th><th>Course Description</th><th>Course Instructor</th><th>Meeting Times</th><th>Max Enrollment</th></tr>";
    foreach ($jsonResult->data as $key => $value) {
        $props = get_object_vars($jsonResult->data[$key]);
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

//link to return to main page
print "<br>";
print "<a href=\"../index.php\">Return to Main Page</a>";

print "</body>";
print "</html>";