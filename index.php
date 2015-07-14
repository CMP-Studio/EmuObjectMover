<?php
  require_once "./config.php";
?>

<html>
  <head>
    <?php head(); ?>
  </head>
  <body>
    <div class="container">

      <form class="form-signin" action="./home/index.php">
        <h2 class="form-signin-heading">Please Sign In</h2>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
        <div id='buttons'>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        </div>
      </form>

    </div>
  </body>
</html>
