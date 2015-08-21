<?php
require_once '../config.php';
require_once filepath() . "plugins/tcpdf/tcpdf.php";
require_once "view.php";

$s_pdf = $_GET['p'];

// create new PDF document
$pdf = new TCPDF('L', 'in', 'LETTER', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('EMu Mover');
$pdf->SetTitle('Project Title');
$pdf->SetSubject('Project');
$pdf->SetKeywords('EMu, Project');

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

$html = '';

$css = file_get_contents("report.css");


$html .= "\n
<html>
  <head>
    
 <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
 <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
 <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<style>\n$css\n</style>
</head>
<body class='report'>
  
    <div id='header' class='padded relative'>
      <div>
        <h1>Project Title</h1>
        <h3>Due: 7/10/2015</h3>
        
      </div>
    </div>
    <div id='objects' >
      <table class='report-table'>
        <thead>
          <tr>
            <th width='16.6%'>Current Location</th>
            <th width='50%'>Object</th>
            <th width='16.6%'>Specific Location</th>
            <th width='16.6%'>Audit</th>
          </tr>
        </thead>
        <tbody>";

$html .= generateObjectRows(getProjectID('00960063'));

$html .= "</tbody></table></body></html>";


if($s_pdf)
{

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('test.pdf', 'I');

exit();

}
else
{
	print $html;
}




?>