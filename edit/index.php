<?php
session_start();
require_once '../config.php';
require_once filepath() . "app/project.php";


if(isset($_POST['project']))
{
  //Existing project - from Home
  $project = $_POST['project'];

  $_SESSION['project'] = $project;

  $info = getProjectInfo();
}
else if(isset($_SESSION['project']))
{
  //Project set in session
  $info = getProjectInfo();
}
else if(isset($_POST['newProj']))
{
  //Create new project
  $info = createProject();
}
else
{
  //Send to home
  exit();
  
}
?>
<html>
  <head>
    <?php
      head();
    ?>
    <script type="text/javascript" src="edit.js"></script>
  </head>
  <body>
      <?php topbar($info['title'], true); ?>
    <div id='header' class='padded relative clearfix'>
      <?php 
      
      if($info['duedate'] != '0000-00-00 00:00:00') { ?>
        <h2 class="left">Due: <?php print date('M j, Y', strtotime($info["duedate"]));?></h2>
        <?php } 
          $hash = $info['hash'];
          $plink = "http://" . $_SERVER['SERVER_NAME'] . sitepath() . 'view?p=' . $hash;
        ?>
        <a target = "_blnak" href="<?php print $plink; ?>" download><button id='btnSave' class="btn btn-success">Export to PDF</button></a>
        <form action='<?php print sitepath(); ?>add'>
          <button id='btnAdd' type="submit" class="btn btn-info">Add Objects</button>
        </form>
    </div>
    <div id='objects'>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Image</th>
            <th>Acc. No.</th>
            <th>Title</th>
            <th>Delete</th>
          </tr>
        </thead>
        <!-- <button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button> -->
        <tbody id='object-body'>


              

        </tbody>
      </table>
    </div>
  </body>
</html>
