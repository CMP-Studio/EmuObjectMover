<?php
require_once __DIR__ . "/../config.php";

require_once IMUapi() . "IMu.php";
require_once IMu::$lib . '/Session.php';
require_once IMu::$lib . '/Module.php';
require_once IMu::$lib . '/Terms.php';

//print "<pre>IMu found: " . IMu::VERSION . "</pre>";

function IMuConnect()
{
  $session =  new IMuSession(IMuServer(), IMuPort() );
  $session->connect();
  return $session;

}





?>
