<?php
require_once "../config.php";
require_once "view.php";

if(isset($_GET["p"]))
{
  $id = getProjectID($_GET["p"]);
}
else
{
  $id = -1;
  //exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <?php
      head();
    ?>
    <title>Move Report</title>
  </head>
  <body class="report">
  
    <div id="header" class="padded relative">
      <div>
        <h1>Project Title</h1>
        <h3>Due: 7/10/2015</h3>
        
      </div>
    </div>
    <div id="objects" >
      <table summary="Report Table" class="report-table">
        <thead>
          <tr>
            <th width="16%">Current Location</th>
            <th width="50%">Object</th>
            <th width="16%">Specific Location</th>
            <th width="16%">Audit</th>
          </tr>
        </thead>
        <tbody>
<?php print generateObjectRows($id); ?>
        </tbody>
      </table>
    </div>
  </body>
</html>
