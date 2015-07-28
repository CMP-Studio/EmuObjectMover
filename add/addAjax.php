<?php
error_reporting(0);

require_once "../config.php";
require_once filepath() . "app/imu.php";
require_once filepath() . "app/sql.php";

addSingleObject();

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
  $number = 100;

  try
  {
    $hits = $events->findTerms($terms);
    $result = $events->fetch('start',$start,$number,$columns);

    print json_encode($result);

  } catch (exception $e) {
    print "<pre>One:\n";
    var_dump($e);
    print "</pre>";
  }

}
/* Just passes through to record object */
function addSingleObject()
{
  if(!isset($_GET['irn'])) return null;

  $irn = $_GET['irn'];
  recordObject($irn);

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
    'image.resource{height:100,source:thumbnail,source:master}'
  );

  $terms->add('irn',trim($irn));

  $start = 0;
  $number = 100;

  try
  {
    $hits = $cat->findTerms($terms);
    $result = $cat->fetch('start',$start,$number,$columns);
    $result = formatResults($result);
  print "<pre>";
    print_r($result);
print "</pre>";
  insertRecord($result);
  } catch (exception $e) {
    print "<pre>Two\n";
    var_dump($e);
    print "</pre>";
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
        $creator[$k2]['Role'] = $rs[$k2];
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
        $measurments[$k3]["Width"] = $ws[$k3];
        $measurments[$k3]["Height"] = $hs[$k3];
        $measurments[$k3]["Depth"] = $ds[$k3];

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

}

/* Instead of updating we will just delete the old records.  All records are tied to the IRN so it won't orphan the old records */
function deleteExistingRecord($record)
{
    $query = "DELETE FROM Objects WHERE IRN = " . $record['irn'];




    $query = "DELETE FROM Children WHERE ParentIRN = " . $record['irn'];

    $query = "DELETE FROM Creators WHERE ObjectIRN = " . $record['irn'];

    $query = "DELETE FROM Measurements WHERE ObjectIRN = " . $record['irn'];
}

function insertRecord($record)
{
  $query = "INSERT INTO Objects (irn, accession_no, barcode, title, year, location_name, location_barcode, image_url) VALUES ("
  . sqlSafe($record['irn']) . "," . sqlSafe($record['AccNo']) . "," . sqlSafe($record["Barcode"]) . "," . sqlSafe($record["Title"]) . "," . sqlSafe($record["Year"]) .
  "," . sqlSafe($record["Location"]["LocLocationName"]) . "," . sqlSafe($record["Location"]["LocBarcode"]) . "," . sqlSafe($record["image"]) . ")";

  runQuery($query);


}
function insertChildRecords($record)
{
  $children = $record["Children"];
  foreach ($children as $key => $ch) 
  {
      $query = "INSERT INTO Children (irn, parent_irn, barcode, summary, location_name, location_barcode) VALUES (" .
        $ch["irn"] . "," . $record["irn"] . "," . $ch["TitBarcode"] ."," . $ch["SummaryData"] . "," . $ch["Location"]["LocLocationName"] . "," . $ch["Location"]["LocBarcode"] . ")";
        
  } 
}



  ;
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
