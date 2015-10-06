<?php
require_once "../config.php";


function clearTempDir()
{
  $path = filepath() . "temp/*";
  clearDir($path);
}

function clearDir($path)
{
  $files = glob($path);
  foreach ($files as $key => $f)
  {
    if(is_file($f))
    {
      unlink($f); //delete
    }
  }
}

?>
