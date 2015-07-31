<?php

//Test
//http://it-svr-emu03/mover/add/addAjax.php?irn=90991
//error_reporting(0);

require_once "../config.php";
require_once filepath() . "app/imu.php";
require_once filepath() . "app/sql.php";

header('Content-Type: application/json');


/* Check variable */
if(!isset($_GET['action']))
{
  $errors = array("source"=>"main","error"=>"Action not set, exiting");
  throwError($errors);
}
if(!isset($_GET['project']))
{
  $errors = array("source"=>"main","error"=>"Project not set, exiting");
  throwError($errors);
}
if(!isset($_GET['irn']))
{
  $errors = array("source"=>"main","error"=>"IRN not set, exiting");
  throwError($errors);
}



$action = $_GET['action'];

switch($action)
{
  case 'check':
    checkInProject();
    break;
  case 'single':
    addSingleObject(); 
    break;
  case 'event':
    addEventObjects();
    break;


  default:
    $errors = array("source"=>"main","error"=>"Action not valid, exiting");
    throwError($errors);

}





function addEventObjects()
{
  if(!isset($_GET['irn'])) return null;

  $irn = $_GET['irn'];

  $mySession = IMuConnect();

  $terms = new IMuTerms();

  $events = new IMuModule('eevents', $mySession);

  $columns = array(
    'objects=ObjAttachedObjectsRef_tab.(irn)',
  );

  $terms->add('irn',trim($irn));

  $start = 0;
  $number = 200;

  try
  {
    $hits = $events->findTerms($terms);
    $result = $events->fetch('start',$start,$number,$columns);



    $objects = $result->rows[0]['objects'];

    foreach ($objects as $key => $o) {
      recordObject($o['irn']);
    }
    success();
  } catch (exception $e) {

    throwError($e);

  }

}
/* Just passes through to record object */
function addSingleObject()
{
  if(!isset($_GET['irn'])) return null;

  $irn = $_GET['irn'];
  recordObject($irn);

  success();

}

function checkInProject()
{
  $irn = $_GET['irn'];
  $project = $_GET['project'];

  $query = "SELECT * FROM objectProject WHERE object_irn = " . sqlSafe($irn) . " AND project_id = " . sqlSafe($project);
  if(hasSQLerrors())
  {
    throwError(getSQLerrors());
  }

  $result = readQuery($query);
  if($result->num_rows > 0)
  {
    $result = array("in_project" => true);
  }
  else
  {
    $result = array("in_project" => false);
  }

  print json_encode($result);
  exit(0);

}

function recordObject($irn)
{

  $mySession = IMuConnect();

  $terms = new IMuTerms();

  $cat = new IMuModule('ecatalogue', $mySession);

  $columns = array(
    'irn',
    'Creator=CreCreatorRef_tab.(Name=NamFullName)',
    'Role=CreRole_tab',
    'AccNo=TitAccessionNo',
    'Title=TitMainTitle',
    'Year=CreDateCreated',
    'Location=LocCurrentLocationRef.(LocLocationName,LocBarcode)',
    'Children=<ecatalogue:AssParentObjectRef>.(irn, SummaryData, Location=LocCurrentLocationRef.(LocLocationName, LocBarcode), TitBarcode)',
    'MesType=MesMeasurementType_tab',
    'H=MesTotalInchFrac_tab',
    'W=MesTotWidthInchFrac_tab',
    'D=MesTotDepthInchFrac_tab',
    'Barcode=TitBarcode',
    'image.resource{height:300,source:master,source:thumbnail}'
  );

  $terms->add('irn',trim($irn));

  $start = 0;
  $number = 100;

  try
  {
    $hits = $cat->findTerms($terms);
    $result = $cat->fetch('start',$start,$number,$columns);
    $result = formatResults($result);

    createRecords($result);

    

  } catch (exception $e) {

    throwError($e);

  }
}

function recordHolder($irn)
{

}

function formatResults($result)
{

  $rows = $result->rows;



    foreach ($rows as $key => $r)
   {

     //Fix creators
      $cs = $r["Creator"];
      $rs = $r["Role"];

      $creator = array();
      foreach ($cs as $k2 => $c)
      {
        $creator[$k2]['Name'] = $c['Name'];
        if(isset($rs[$k2]))
        {
          $creator[$k2]['Role'] = $rs[$k2];
        }
        else
        {
          $creator[$k2]['Role'] = '';
        }
      }

      $result->rows[$key]["Creator"] = $creator;
      unset($result->rows[$key]['Role']);

      //Fix measurements
      $ms = $r['MesType'];
      $hs = $r["H"];
      $ws = $r["W"];
      $ds = $r["D"];

      $measurments = array();
      foreach ($ms as $k3 => $m)
      {
        $measurments[$k3]['Type'] = $m;
        $measurments[$k3]["Width"] = tryHash($ws, $k3);
        $measurments[$k3]["Height"] = tryHash($hs, $k3);
        $measurments[$k3]["Depth"] = tryHash($ds, $k3);

      }

      $result->rows[$key]['Measurements'] = $measurments;
      unset($result->rows[$key]['MesType']);
      unset($result->rows[$key]['W']);
      unset($result->rows[$key]['H']);
      unset($result->rows[$key]['D']);

      //Fix image
      $image = $r['image']["resource"];
      $imgname = $r['image']["resource"]['identifier'];

      if($imgname)
      {
        $imgloc = IMuImageLoc() . $imgname;
        saveImg($imgloc, $image);
        $result->rows[$key]['image'] = IMuImageURL() . $imgname;
      }
      else
      {
        $result->rows[$key]['image'] = null;
      }
      return $result->rows[$key];


   }

   return null;
}

function createRecords($record)
{
  deleteExistingRecords($record);
  insertRecord($record);
  insertChildRecords($record);
  insertMeasurements($record);
  insertCreators($record);


  if(hasSQLerrors())
  {
    deleteExistingRecords($record);
    throwError(getSQLerrors());
  }
  else
  {
    attachObject($record);
    if(hasSQLerrors())
    {
      throwError(getSQLerrors());
    }
    return true;
  }

}

/* Instead of updating we will just delete the old records.  All records are tied to the IRN so it won't orphan the old records */
function deleteExistingRecords($record)
{
    $result = true;
    $project = $_GET['project'];
    $query = "DELETE FROM objects WHERE irn = " . sqlSafe($record['irn']);
    $result = $result && writeQuery($query);
    $query = "DELETE FROM children WHERE parent_irn = " . sqlSafe($record['irn']);
    $result = $result && writeQuery($query);
    $query = "DELETE FROM creators WHERE object_irn = " . sqlSafe($record['irn']);
    $result = $result && writeQuery($query);
    $query = "DELETE FROM measurements WHERE object_irn = " . sqlSafe($record['irn']);
    $result = $result && writeQuery($query);
    $query = "DELETE FROM objectProject WHERE object_irn = " . sqlSafe($record['irn']) . " AND project_id = " . sqlSafe($project);
    $result = $result && writeQuery($query);

    return $result;
}

function insertRecord($record)
{
  $query = "INSERT INTO objects (irn, accession_no, barcode, title, year, location_name, location_barcode, image_url) VALUES ("
  . sqlSafe($record['irn']) . "," . sqlSafe($record['AccNo']) . "," . sqlSafe($record["Barcode"]) . "," . sqlSafe($record["Title"]) . "," . sqlSafe($record["Year"]) .
  "," . sqlSafe($record["Location"]["LocLocationName"]) . "," . sqlSafe($record["Location"]["LocBarcode"]) . "," . sqlSafe($record["image"]) . ")";

  $result = writeQuery($query);

  return $result;

}
function insertChildRecords($record)
{
  $children = $record["Children"];
  $result = true;
  foreach ($children as $key => $ch) 
  {
      $query = "INSERT INTO children (irn, parent_irn, barcode, summary, location_name, location_barcode) VALUES (" .
        sqlSafe($ch["irn"]) . "," . sqlSafe($record["irn"]) . "," . sqlSafe($ch["TitBarcode"]) ."," . sqlSafe($ch["SummaryData"])
         . "," . sqlSafe($ch["Location"]["LocLocationName"]) . "," . sqlSafe($ch["Location"]["LocBarcode"]) . ")";
        
        $result = $result && writeQuery($query);
  } 
  return $result;
}

function insertMeasurements($record)
{
  $measure = $record["Measurements"];
  foreach ($measure as $key => $m) 
  {
    $type = tryHash($m, "Type");
    $w = tryHash($m, "Width");
    $h = tryHash($m, "Height");
    $d = tryHash($m, "Depth");

    $query = "INSERT INTO `emuProjects`.`measurements` (`object_irn`, `type`, `width`, `height`, `depth`) VALUES(" .
      sqlSafe($record["irn"]) . "," . sqlSafe($type) . "," . sqlSafe($w) . "," . sqlSafe($h) .
      "," . sqlSafe($d) . ")";
      writeQuery($query);
  }
}

function insertCreators($record)
{
  $cre = $record["Creator"];
  $irn = $record["irn"];
  foreach ($cre as $key => $c) 
  {
      $name = tryHash($c, "Name");
      $role = tryHash($c, "Role");

      $query = "INSERT INTO `emuProjects`.`creators` (`object_irn`, `name`, `role`) VALUES ( ".
        sqlSafe($irn) . "," . sqlSafe($name) . "," . sqlSafe($role) . ")";

      writeQuery($query);
  }
}
function attachObject($record)
{
  $project = $_GET['project'];
  $object = $record['irn'];

  $query = "INSERT INTO `emuProjects`.`objectProject` (`project_id`, `object_irn`) VALUES (" .
    sqlSafe($project) . "," . sqlSafe($object) . ")";

  writeQuery($query);

}

function tryHash($array, $key)
{
  if(isset($array[$key])) return $array[$key];

  return null;
}

function throwError($errors)
{
  $response = array('success' => false, 'errors' => $errors);
  print json_encode($response);
  exit(-1);
}
function success()
{
  $response = array('success' => true);
  print json_encode($response);
  exit(0);
}


function saveImg($newloc, $image)
{
  // Save a copy of the resource
  $temp = $image['file'];

  $copy = fopen( $newloc, 'wb');
  for (;;)
  {
     $data = fread($temp, 4096); // read 4K at a time
     if ($data === false || strlen($data) == 0)
     break;
     fwrite($copy, $data);
  }
  fclose($copy);
}


 ?>
