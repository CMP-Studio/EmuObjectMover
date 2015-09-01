<?php
 
  require_once "../config.php";
  require_once "projects.php";
  require_once filepath() . "app/auth.php";

  if(isset($_POST['login']))
  {
    if(authorize($_POST['email']))
    {

    }
    else
    {
      redirect(sitepath());
    }
  }
  else if(isset($_POST["register"]))
  {
    createAccount($_POST['email'], $_POST['name']);
  }
  else
  {
     if(checkAuth())
     {

     }
     else
     {
      redirect(sitepath());
     }
  }


  if(isset($_POST["projectToDelete"]))
  {
    deleteProject($_POST["projectToDelete"]);
  }



?>

<html>
  <head>
    <?php head(); ?>
    <script type="text/javascript" src="project.js"></script>
  </head>
  <body>
    <?php topbar() ?>
    <div id='main'>
      <div class="padded">
        <form action="../create/">
          <button class="btn btn-lg btn-primary btn-block" type="submit">Create a Project</button>
        </form>
      </div>
      <div id="listing">
        <table class="table table-striped">
          <thead>
            <tr>
              <th width="15%">Project Name</th>
              <th width="50%">Notes</th>
              <th width="10%">Objects</th>
              <th width="15%">Due</th>
              <th width="5%">Details</th>
              <th width="5%">Delete</th>
            </tr>
          </thead>
          <tbody id='projects'>
            <?php
              $projects = getProjects(getAccount());
              //var_dump($projects);
              

              foreach ($projects as $key => $p) {
                print "<tr>\n";
                print "\t<td>" . $p["title"] . "</td>\n";
                print "\t<td>" . $p["notes"] . "</td>\n";
                if(!isset($p["nObjects"]))
                {
                  print "\t<td>0</td>\n";
                }
                else
                {
                  print "\t<td>" . $p["nObjects"] . "</td>\n";
                }
                if($p['duedate'] != '0000-00-00 00:00:00') {
                  print "\t<td>" . date('M j, Y', strtotime($p["duedate"])) . "</td>\n";
                }
                else
                {
                  print "\t<td>None</td>\n";
                }
                ?>
                 <td>
                  <form action='../view/' method="POST">
                      <input type='hidden' name='project' value='<?php print $p['id']; ?>'/>
                      <button type="submit" class="btn btn-large btn-info"><i class="fa fa-eye"></i></button>
                  </form>
                </td>
                <td>
                  <button class="btn btn-large btn-danger delete-project" data-target="<?php print $p['id']; ?>"><i class="fa fa-trash-o"></i></button>
                </td>
                <?php
                print "</tr>\n";
              }

              
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </body>
</html>
