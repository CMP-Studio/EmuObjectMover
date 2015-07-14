<?php
require_once '../config.php';
?>
<html>
  <head>
    <?php
      head();
    ?>
  </head>
  <body>
      <?php topbar("Project Title"); ?>
    <div id='header' class='padded relative'>
      <div>
        <h3>Due: 7/10/2015</h3>
      </div>
        <form action='<?php print sitepath(); ?>add'>
          <button id='btnAdd' type="submit" class="btn btn-success">Add Objects</button>
        </div>
    </div>
    <div id='objects'>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Image</th>
            <th>Object Name</th>
            <th>Type</th>
            <th>Completed</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>No Image</td>
            <td>The Orchard by Gustav Klimt</td>
            <td>Object</td>
            <td><input id='chkDone-1' type='checkbox'></td>
            <td>
              <!--<button type="button" class="btn btn-info"><i class="fa fa-cog"></i></button>-->
              <button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>
