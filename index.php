<?php
  require_once "./config.php";
  require_once filepath() . "app/auth.php";

  if(isset($_POST["logout"]))
  {
    deauthorize();
  }
?>

<html>
  <head>
    <?php head(); ?>
  </head>
  <body>
    <div class="container">
      <div class="form-signin">
        <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
          <li class="active"><a href="#login" data-toggle="tab">Sign In</a></li>
          <li ><a href="#register" data-toggle="tab">Register</a></li>
        </ul>
        <div id="login-signup" class="tab-content">
          <div class="tab-pane active" id="login">
            <form method="post" action="<?php print sitepath() ?>home/">
              <h2 class="form-signin-heading">Please Sign In</h2>
              <p class='auth-error'><?php print getInvalidReason() ?></p>
              <label for="inputEmail" class="sr-only">Email address</label>
              <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
              <div id='buttons'>
                <button class="btn btn-lg btn-primary btn-block" name="login" type="submit" default>Sign in</button>
              </div>
            </form>
          </div>
          <div class="tab-pane" id="register">
            <form method="post" action="<?php print sitepath() ?>home/">
               <h2 class="form-signin-heading">New User</h2>
              <label for="inputName" class="sr-only">Full Name</label>
              <input type="text" name="name" id="inputName" class="form-control" placeholder="Your Name" required="">
              <label for="inputEmail" class="sr-only">Email address</label>
              <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required="">
              <div id='buttons'>
                <button class="btn btn-lg btn-primary btn-block" name="register" type="submit">Register</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
