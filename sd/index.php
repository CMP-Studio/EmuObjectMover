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
      <name>Date work needs to be complete:</name>
      <value>9/24/2015</value>
    </parameter>
    <parameter>
      <name>Subject</name>
      <value>API Test</value>
    </parameter>
  </Details>
</Operation>";

$key = getSDkey();
$method = "ADD_REQUEST";

$postvars = array("OPERATION_NAME" => $method, "TECHNICIAN_KEY" => $key, "INPUT_DATA" => $input);

$url = getSDBaseURL() . "sdpapi/request/";

var_dump(postAPI($url, $postvars, null, false));



 ?>
