<?php
require_once '../config.php';
?>
<html>
  <head>
    <?php
      head();
    ?>
    <script type="text/javascript" src="edit.js"></script>
        <script type="text/javascript"> 
    setProject(2);

    </script>
  </head>
  <body>
      <?php topbar("Project Title", 2); ?>
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
            <th>Acc. No.</th>
            <th>Title</th>
            <th>Actions</th>
          </tr>
        </thead>
        <!-- <button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button> -->
        <tbody id='object-body'>


              

        </tbody>
      </table>
    </div>
  </body>
</html>
