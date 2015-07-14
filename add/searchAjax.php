<?php
error_reporting(0);

require_once "../config.php";
require_once filepath() . "app/imu.php";


if(isset($_GET["m"]))
{
  $mode = $_GET["m"];

  switch ($mode) {
    case 'single': searchObject();    break;
    case 'holder': searchHolder();    break;

    default: exit();   break;
  }
}



function searchObject()
{
  $mySession = IMuConnect();

  $terms = new IMuTerms();

  $columns =  array(
	'SummaryData',
  'image.resource{height:100,source:thumbnail,source:master}'
	);

  if(isset($_GET["accnum"]))
  {
    $terms->add('TitAccessionNo', trim($_GET["accnum"]), '=' );
  }
  if(isset($_GET["title"]))
  {
    $terms->add('TitMainTitle', trim($_GET["title"]));
  }
  if(isset($_GET["creator"]))
  {
    $terms->add('CreCreatorLocal_tab', trim($_GET["creator"])); //CreCreatorName
  }
  if(isset($_GET["barcode"]))
  {
    $terms->add('TitBarcode', trim($_GET["barcode"]));
  }
  if(isset($_GET["irn"]))
  {
    $terms->add('irn', trim($_GET["irn"]));
  }

  $catalogue = new IMuModule('ecatalogue', $mySession);

  $start = 0;
  if(isset($_GET["start"]))
  {
    $start = intval($_GET["start"]);
  }
  $number = 15;
  if(isset($_GET["n"]))
  {
    $number = intval($_GET["n"]);
  }



  try
  {
    $hits = $catalogue->findTerms($terms);
    $result = $catalogue->fetch('start',$start,$number,$columns);

    $rows = $result->rows;

    //Process images
    foreach ($rows as $key => $r) {
      $image = $r['image']["resource"];
      $imgname = $image["identifier"];
      if(!empty($imgname))
      {
        $imgloc = IMuImageLoc() . $imgname;
        $imgurl = IMuImageURL() . $imgname;
        saveImg($imgloc, $image);
      }
      else {
        $imgurl = null;
      }

      $result->rows[$key]["image"] = $imgurl;

    }

    print json_encode($result);

  }
  catch (Exception $e)
  {

    print var_dump($e);
  }



}

function searchHolder()
{
  $mySession = IMuConnect();


  $terms = new IMuTerms();

  $columns =  array(
  'SummaryData',
  'LocLocationType',
  'image.resource{height:100,source:master}'
  );

  //$terms->add("LocLocationType", "Holder");

  //LocHolderName
  if(isset($_GET["name"]))
  {
    $terms->add('LocHolderName', trim($_GET["name"]));
  }
  //LocBarcode
  if(isset($_GET["barcode"]))
  {
    $terms->add('LocBarcode', trim($_GET["barcode"]));
  }
  if(isset($_GET["irn"]))
  {
    $terms->add('irn', trim($_GET["irn"]));
  }



    $locations = new IMuModule('elocations', $mySession);

    $start = 0;
    if(isset($_GET["start"]))
    {
      $start = intval($_GET["start"]);
    }
    $number = 15;
    if(isset($_GET["n"]))
    {
      $number = intval($_GET["n"]);
    }



    try
    {
      $hits = $locations->findTerms($terms);
      $result = $locations->fetch('start',$start,$number,$columns);
      var_dump($result);
      $rows = $result->rows;

      //Process images
      foreach ($rows as $key => $r) {
        $image = $r['image']["resource"];
        $imgname = $image["identifier"];
        if(!empty($imgname))
        {
          $imgloc = IMuImageLoc() . $imgname;
          $imgurl = IMuImageURL() . $imgname;
          saveImg($imgloc, $image);
        }
        else {
          $imgurl = null;
        }



        $result->rows[$key]["image"] = $imgurl;

        var_dump($result->rows[$key]);

      }

      print json_encode($result);

    }
    catch (Exception $e)
    {

      var_dump($e);
    }


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
