<?php
session_start();

  require_once "../config.php";
  $sitepath = sitepath();
  unset($_SESSION['project']);
?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <title>Create a Project</title>
    <?php head(); ?>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#projDue').datepicker();
      });
    </script>
  </head>
  <body>
    <?php topbar(); ?>
    <div class='form'>
      <form id='createProj' action="../edit/" method="post">
          <label for="projName"  class="sr-only">Project Name</label>     
        <input type="text" id="projName"  name="projName"  class="form-control" placeholder="Project Name" required="" autofocus=""/>
          <label for="projDue"  class="sr-only">Due Date</label>         
        <input type="text" id="projDue"  name="projDue"   class="form-control" placeholder="Due Date" required=""/>
        <button id="newProj" class="btn btn-lg btn-primary btn-block" name="newProj" type="submit">Create this Project</button>
      </form>
    </div>
  </body>
</html>
