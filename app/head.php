<?php
require_once __DIR__ . "/../config.php";


function head()
{

  $sitepath = sitepath();
 ?>

 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge" />
 <meta name="viewport" content="width=device-width, initial-scale=1" />
 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

<!-- jQuery -->
 <script src="https://code.jquery.com/jquery-1.11.3.min.js" type="text/javascript"></script>

 <!-- jQuery UI -->
 <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
 <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />

 <!-- Bootstrap CSS -->
 <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />

 <!-- Bootstrap Theme CSS -->
 <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" />

 <!-- Bootstrap JS -->
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" type="text/javascript"></script>

 <!-- Font Awesome -->
 <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" />

<!-- Date picker -->
<script type="text/javascript" src="<?php print $sitepath; ?>resources/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php print $sitepath; ?>resources/css/bootstrap-datepicker3.css" />

 <!-- Main CSS -->
 <link rel="stylesheet" type="text/css" href="<?php print $sitepath; ?>resources/css/main.css" />

 <?php

}
  ?>
