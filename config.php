<?php
/* This file makes it so that as long as the page knows where this file is located all other paths are easy */
  require_once filepath() . "app/head.php";
    require_once filepath() . "app/topbar.php";

//The web server filepath (i.e. start at webroot)
  function sitepath()
  {
    return "/mover/";
  }

  //The base server file path (i.e. start at root)
  function filepath()
  {
    return "/var/www/html/mover/";
  }

  // ex mover.cmoa.org //
  function webdomain()
  {
    return 'http://it-svr-emu03';
  }

  function siteurl()
  {
    return webdomain() . sitepath();
  }

  function IMUapi()
  {
    return "/var/www/html/imu-api/";
  }

  function IMuServer()
  {
    return "localhost";
  }
  function IMuImageLoc()
  {
    return filepath() . "emuimgs/";
  }
  function IMuImageURL()
  {
    return sitepath() . "emuimgs/";
  }

  function IMuPort()
  {
    /*
    --- IMu ports ---

    test = 40002
    cma = 40082
    */

    return 40082;
  }

 ?>
