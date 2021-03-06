<?php
require_once "../config.php";
require_once "genPDF.php";
require_once "objects.php";

require_once filepath() . "app/config/sdConfig.php";
require_once filepath() . "app/api.php";



function genSD($projectID)
{
  $file = genPDF($projectID, true);
  $info = getProjectInfo($projectID);

  $xml = "<Operation>
    <Details>";

  $xml .= addParam("requesttemplate","API");
  $xml .= addParam("requester",$info["name"]);
  $xml .= addParam("email",$info["email"]);
  $xml .= addParam("subject",$info["title"]);
  $xml .= addParam("description",$info["notes"]);

  $date = strtotime($info["duedate"]);
  $datestr = date("j F Y", $date) . ", 23:59:59";

  $xml .= addParam("Due Date",$datestr);

  $xml .= "</Details>
          </Operation>";

  $key = getSDkey();
  $method = "ADD_REQUEST";

  $postvars = array("OPERATION_NAME" => $method, "TECHNICIAN_KEY" => $key, "INPUT_DATA" => $xml);

  $url = getSDBaseURL() . "sdpapi/request/";
  $res = postAPI($url, $postvars, null, false);
  $resp = simplexml_load_string($res);
  if(isset($resp->response->operation->Details[0]->workorderid))
  {
    $wo = $resp->response->operation->Details[0]->workorderid;
    $url = getSDBaseURL() . "sdpapi/request/$wo/attachment?OPERATION_NAME=ADD_ATTACHMENT&TECHNICIAN_KEY=$key";

    $fields = array("file" => '@' . $file);

    $headers = array('Content-Type: multipart/form-data');
    $res = postAPI($url, $fields, $headers, false, false);
    $resp = simplexml_load_string($res);
    if(isset($resp->response->operation->result->status))
    {
      if($resp->response->operation->result->status == "Success")
      {
        //complete
      }
    }
    return  array('url' => getSDBaseURL() . "WorkOrder.do?woMode=viewWO&woID=$wo", 'ID' => $wo);
  }
  else {
    return null;
  }

}

function addParam($key, $val)
{
  return "<parameter>
    <name>$key</name>
    <value>$val</value>
  </parameter>";

}


 ?>
