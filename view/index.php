<?php

require_once '../config.php';
require_once filepath() . "app/project.php";
require_once filepath() . "app/auth.php";

     if(checkAuth())
     {

     }
     else
     {
      redirect(sitepath());
     }


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
    <script type="text/javascript" src="view.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#date-control').datepicker({
          format: "yyyy-mm-dd"
        });
      });
    </script>
  </head>
  <body>
      <?php topbar($info['title'], true); ?>
    <div id='header' class='padded relative clearfix'>
    <table class="projectDetail">
      <tr>
        <th class="key">
          Due by
        </th>
        <td class="field">
          <span id="due-date">
          <?php if($info['duedate'] == '0000-00-00 00:00:00') { ?>
          None
          <?php } else {
          print date('Y-m-d', strtotime($info["duedate"]));
          }?>
          </span>
          <input type="text" id="date-control"  name="DueDate"   class="edit-control" placeholder="Due Date"/>
        </td>
        <td>
          <button id='edit-date' class='nobutton edit-btn' toggle="edit" data-key="duedate"  input-target="#date-control" display-target="#due-date"><i class='fa fa-pencil'></i></button>
        </td>
      </tr>
      <tr>
        <th class="key">
          Move To
        </th>
        <td class="field" >
          <span id="move-to">
          <?php 
          print $info["moveto"];
          ?>
          </span>
          <input type="text" id="move-control"  name="MoveTo"   class="edit-control" placeholder="Move To"/>
        </td>
        <td>
           <button id='edit-move' class='nobutton edit-btn' data-key="moveto" toggle="edit" input-target="#move-control" display-target="#move-to"><i class='fa fa-pencil'></i></button>
        </td>
      </tr>
      <tr>
        <th class="key">
          Notes
        </th>
        <td class="field" >
         <button id='edit-note' class='nobutton edit-btn' data-key="notes"  toggle="edit" input-target="#note-control" display-target="#notes"><i class='fa fa-pencil'></i></button>
        </td>
        <td>
                   </td>
      </tr>
      <tr>
        <td colspan="3">
          <span id="notes">
          <?php 
          print $info["notes"];
          ?>
          </span>
          <textarea id="note-control" class="edit-control">
          </textarea>
        </td>
      </tr>
       
    </table>
      <div id="projButtons">
        <?php 
          $hash = $info['hash'];
          $plink = "http://" . $_SERVER['SERVER_NAME'] . sitepath() . 'view/genPDF.php?p=' . $hash;
        ?>
        <a target = "_blank" href="<?php print $plink; ?>" download><button id='btnSave' class="btn btn-success">Export to PDF</button></a>
        <form action='<?php print sitepath(); ?>add'>
          <button id='btnAdd' type="submit" class="btn btn-info">Add Objects</button>
        </form>
      </div>
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
