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
    <div id='header' class='padded relative'>
      <div>
        <h1>Project Title</h1>
        <h3>Due: 7/10/2015</h3>
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

          </tr>
        </thead>
        <tbody>
          <tr>
            <td>No Image</td>
            <td>The Orchard by Gustav Klimt</td>
            <td>Object</td>
            <td><input id='chkDone-1' type='checkbox'></td>

          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>
