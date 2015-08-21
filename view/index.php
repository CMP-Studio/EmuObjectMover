<?php
require_once '../config.php';
require_once "view.php";

if(isset($_GET['p']))
{
  $id = getProjectID($_GET['p']);
}
else
{
  $id = -1;
  //exit();
}

?>
<html>
  <head>
    <?php
      head();
    ?>
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
        <tbody>
<?php print generateObjectRows($id); ?>
        </tbody>
      </table>
    </div>
  </body>
</html>
