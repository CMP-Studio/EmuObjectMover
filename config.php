<?php
/* Ensure we have a session running */
function is_session_started()
{
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}
if ( is_session_started() === FALSE ) session_start();


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
  function IMuTmpImageLoc()
  {
    return filepath() . "tmpimgs/";
  }
  function IMuTmpImageURL()
  {
    return sitepath() . "tmpimgs/";
  }

function redirect($url){
    if (headers_sent()){
      die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
    }else{
      header('Location: ' . $url);
      die();
    }    
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
