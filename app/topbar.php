<?php
require_once __DIR__ . "/../config.php";

function topbar($title = "EMuver", $project = false)
{

   ?>

<nav id='topbar' class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- Mobile Navbar -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <!-- Primary nav bar -->
    <div class="collapse navbar-collapse" id="main-nav">
      <ul class="nav navbar-nav">
        <li><a class="navbar-brand" href="<?php print sitepath(); ?>home"><i class="fa fa-home" alt="Home"></i></a></li>
      </ul >
      <ul class="nav navbar-nav navbar-center">
          <li><h3 id='topbar-title'>
          <?php
          if($project)
          {
            print "<a href='" . sitepath() . "view'>" . $title . "</a>";
          }
          else
          {
            print $title;
          }?>
          </h3></li>
      </ul>

      <form class="navbar-form navbar-right" method="post" action="<?php print sitepath(); ?>">
       <button type="submit" name="logout"  class="btn btn-warning">Logout</button>
      </form>



    </div>
  </div>
</nav>

<?php } ?>
