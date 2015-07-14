<?php
  require_once "../config.php";
?>

<html>
  <head>
    <?php head(); ?>
  </head>
  <body>
    <?php topbar() ?>
    <div id='main'>
      <div class="padded">
        <form action="../create/">
          <button class="btn btn-lg btn-primary btn-block" type="submit">Create New</button>
        </form>
      </div>
      <div id="listing">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Project Name</th>
              <th>Objects</th>
              <th>Due</th>
              <th>Edit</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <!-- Replace with generated code -->
              <td>Test</td>
              <td>4</td>
              <td>1/1/16</td>
              <td>
                <button type="button" class="btn btn-info"><i class="fa fa-cog"></i></button>
              </td>
              <!-- End - Replace with generated code -->
          </tbody>
        </table>
      </div>
    </div>
  </body>
</html>
