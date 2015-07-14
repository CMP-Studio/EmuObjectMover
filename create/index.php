<?php
  require_once "../config.php";
  $sitepath = sitepath();
?>

<html>
  <head>
    <?php head(); ?>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#inputDate').datepicker();
    });
  </script>
  </head>
  <body>
    <?php topbar(); ?>
    <div class='form' >
      <form action='<?php print $sitepath; ?>edit'>
        <label for="inputName" class="sr-only">Project Name</label>
        <input type="text" id="inputName" class="form-control" placeholder="Project Name" required="" autofocus="">
        <label for="inputDate" class="sr-only">Due Date</label>
        <input type="text" id="inputDate" class="form-control" placeholder="Due Date">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Create this Project</button>
      </form>
    </div>
  </body>
</html>
