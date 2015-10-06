<?php
require_once "../config.php";
require_once filepath() . "plugins/tcpdf/tcpdf.php";
require_once "objects.php";

function genPDF($proj, $local = false)
{
  // create new PDF document
  $size = array(8.5, 11);

  $pdf = new TCPDF("l", "in", $size, true, "UTF-8", false);

  // set document information
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor("EMu Mover");
  $pdf->SetTitle("Project Title");
  $pdf->SetSubject("Project");
  $pdf->SetKeywords("EMu, Project");
  $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->SetMargins(0.5,0.5,0.5, false);

  setSpacing($pdf);

  // set font
  $pdf->SetFont("helvetica", "", 10);

  // add a page
  $pdf->AddPage();
  $pdf->SetAutoPageBreak(TRUE,0);

  $html = "";

  $css = file_get_contents("report.css");

  $style = "<style type=\"text/css\">\n$css\n</style>";


  $w = $pdf->getPageWidth();
  $h = $pdf->getPageHeight();
  $m = $pdf->getMargins();

  $pdf->setCellPaddings(0.05,0.1,0.05,0);
  $pdf->setCellHeightRatio(0.4);

  $w -= $m['left'] + $m['right'];
  $h -= $m['top'] + $m['bottom'];



  $sixth = $w/6 ;

  $sixth = floor($sixth * 100.0) / 100.0;


  $objs = generatePDFcells($proj);


  addHeaderRow($pdf);

  //Get row heights
  $heights = array();
  foreach ($objs as $k1 => $obj)
  {
    $c = 0;

    $lh = 0;
    $y = $pdf->GetY();
    foreach ($obj as $k2 => $cell)
    {
      $cellWidth = $sixth;
      $x = $pdf->GetX();

      if($c == 1)
      {
        $cellWidth = 3*$sixth;
      }

      $pdf->writeHTMLcell($cellWidth, 0, $x, $y, $style . $cell, 0, 0, 0, 1, '', 1);
      $c++;

      $tlh = $pdf->getLastH();
      //$objs[$k1][$k2] .= "<p>$tlh</p>";
      if($tlh > $lh)
      {
        $lh = $tlh;
      }
    }

    $heights[$k1] = $lh;
    $pdf->deletePage($pdf->getPage());
    $pdf->AddPage();

  }


  addIntro($pdf, $proj);

  $y = $pdf->GetY();
  addHeaderRow($pdf);
  //Now actually create the PDF
  foreach ($objs as $k1 => $obj)
  {
    $y = $pdf->GetY();
    if(isset($heights[$k1]))
    {
      if($y + $heights[$k1] >= $h)
      {
        $pdf->AddPage();
        addHeaderRow($pdf);
      }
    }
    $c = 0;

    $lh = 0;
    $y = $pdf->GetY();
    foreach ($obj as $k2 => $cell)
    {
      $cellWidth = $sixth;
      $x = $pdf->GetX();

      if($c == 1)
      {
        $cellWidth = 3*$sixth;
      }

      $pdf->writeHTMLcell($cellWidth, $heights[$k1], $x, $y, $style . $cell, 'LTRB', 0, 0, 1, '', 1);
      $c++;

    }
    $pdf->SetY($y + $heights[$k1]);

  }



  if($proj)
  {

    $info = getProjectInfo($proj);

    if($local)
    {

      $fname = $info['title'] . ".pdf";
      $val = $pdf->Output($fname, "F");
      return $val;
    }
    else
    {
      header("Content-type: application/pdf");
      $pdfpath = filepath() . "/temp/" . $info['title'] . ".pdf";
      $pdf->Output($pdfpath, "I");
      return $pdfpath;
    }


    return true;
    exit();

  }
  else
  {
    print "$style";
    var_dump($objs);
    return false;
  }
}


function addHeaderRow($pdf)
{

  $pdf->SetFont("helvetica", "B", 10);
  $w = $pdf->getPageWidth();
  $m = $pdf->getMargins();



  $w -= $m['left'] + $m['right'];
  $sixth = $w/6;
  $sixth = floor($sixth * 100.0) / 100.0;

  $pdf->Cell($sixth,0,'Current Location','LTRB',0,'C');
  $pdf->Cell($sixth*3,0,'Object','LTRB',0,'C');
  $pdf->Cell($sixth,0,'Moved To','LTRB',0,'C');
  $pdf->Cell($sixth,0,'Audit','LTRB',1,'C');
  $pdf->SetFont("helvetica", "", 10);

}

function addIntro($pdf, $id)
{
  $info = getProjectInfo($id);

  $pdf->SetFont("helvetica", "", 16);
  $w = $pdf->getPageWidth();
  $m = $pdf->getMargins();
  $w -= $m['left'] + $m['right'];

  $pdf->cell($w, 0, $info['title'],0,1,'C');
  $pdf->setCellHeightRatio(1);

  $cwKey = $w/8;
  $cwVal = $w/2 - $cwKey;

  $pdf->SetFont("helvetica", "B", 10);
  $pdf->MultiCell($cwKey, 0, 'Requested By',0,'R', 0, 0);
  $pdf->SetFont("helvetica", "", 10);
  $pdf->MultiCell($cwVal, 0, $info['name'],0,'L',0,0);
  $lh = $pdf->getLastH();

  $pdf->SetFont("helvetica", "B", 10);
  $pdf->MultiCell($cwKey, 0, 'Move To',0,'R', 0, 0);
  $pdf->SetFont("helvetica", "", 10);
  $pdf->MultiCell($cwVal, 0, $info['moveto'],0,'L',0,1);
  $lh = $pdf->getLastH();

  if(!empty($info['notes']))
  {
    $pdf->SetFont("helvetica", "B", 10);
    $pdf->MultiCell($cwKey, 0, 'Notes',0,'R', 0, 0);
    $pdf->SetFont("helvetica", "", 10);
    $pdf->MultiCell($cwVal, 0, $info['notes'],0,'L',0,0);
    $lh = $pdf->getLastH();
  }
  if($info['duedate'] != '0000-00-00 00:00:00')
  {
    $time = strtotime($info['duedate']);
    $date = date('n/j/Y', $time);
    $pdf->SetFont("helvetica", "B", 10);
    $pdf->MultiCell($cwKey, 0, 'Due',0,'R',0,0);
    $pdf->SetFont("helvetica", "", 10);
    $pdf->MultiCell($cwVal, 0, $date,0,'L',0,0);
  }
  $pdf->SetY($pdf->GetY() + $lh);
  $pdf->Ln();
  $pdf->setCellHeightRatio(0.4);
}
function setSpacing($pdf)
{
  $tagvs = array('div' =>
  array(
    0 => array('h' => '', 'n' => 0),
    1 => array('h' => '', 'n' => 0)
  ),
  'p' =>
  array(
    0 => array('h' => '', 'n' => 0),
    1 => array('h' => '', 'n' => 0)
  ),
  'table' =>
  array(
    0 => array('h' => '', 'n' => 0),
    1 => array('h' => '0.1', 'n' => 1)
  ),
  'tr' =>
  array(
    0 => array('h' => '', 'n' => 0),
    1 => array('h' => '0.1', 'n' => 1)
  )
);

$pdf->setHtmlVSpace($tagvs);
}




?>
