<?php
require_once __DIR__ . "/../config.php";
require_once filepath() . "app/config/sdConfig.php";
require_once filepath() . "app/api.php";

$input =
"<Operation>
  <Details>
    <parameter>
      <name>requesttemplate</name>
      <value>API</value>
    </parameter>
    <parameter>
      <name>Priority</name>
      <value>Normal</value>
    </parameter>
    <parameter>
      <name>Requester</name>
      <value>Russian, Regina</value>
    </parameter>
    <parameter>
      <name>Description</name>
      <value>This is a test</value>
    </parameter>
    <parameter>
      <name>Due Date</name>
      <value>24 September 2015, 12:00:00</value>
    </parameter>
    <parameter>
      <name>Subject</name>
      <value>API Test Part 3</value>
    </parameter>
  </Details>
</Operation>";

$key = getSDkey();
$method = "ADD_REQUEST";

$postvars = array("OPERATION_NAME" => $method, "TECHNICIAN_KEY" => $key, "INPUT_DATA" => $input);

$url = getSDBaseURL() . "sdpapi/request/";

var_dump($url);

var_dump(postAPI($url, $postvars, null, false));



 ?>
