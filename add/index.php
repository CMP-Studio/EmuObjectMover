<?php
session_start();
require_once '../config.php';
require_once filepath() . "app/project.php";

if(!isset($_SESSION['project']))
{
  //Return to home
  exit();
}

$info = getProjectInfo();
?>
<!DOCTYPE html>
<html>
  <head>
    <?php
      head();
    ?>
    <script type="text/javascript" src="search.js"></script>
  </head>
  <body>
    <?php topbar($info['title'], true); ?>
    <div class='padded'>
      <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li class="active"><a href="#single" data-toggle="tab">Single Object</a></li>
        <li ><a href="#holder" data-toggle="tab">Holder</a></li>
        <li ><a href="#group" data-toggle="tab">Group</a></li>
        <li ><a href="#event" data-toggle="tab">Event</a></li>
      </ul>

      <div id="search-content" class="tab-content">
        <div class="tab-pane active" id="single">
          <form class='form search'>
            <label for="inputID" class="sr-only">Acc. No.</label>
            <input type="text" id="inputID" class="form-control small-f" placeholder="Acc. No." autofocus="">
            <label for="inputTitle" class="sr-only">Title</label>
            <input type="text" id="inputTitle" class="form-control large-f" placeholder="Title" >
            <label for="inputCreator" class="sr-only">Creator</label>
            <input type="text" id="inputCreator" class="form-control medium-f" placeholder="Creator" >
            <label for="inputSBarcode" class="sr-only">Barcode</label>
            <input type="text" id="inputSBarcode" class="form-control small-f" placeholder="Barcode" >
            <label for="inputSIRN" class="sr-only">IRN</label>
            <input type="text" id="inputSIRN" class="form-control small-f" placeholder="IRN" >
            <h3 class='small-f N-results'> </h3>
            <button id="single-object" class="btn btn-lg btn-primary right small-f" type="button"><i class="fa fa-search"></i> Search</button>
            <a href="../edit/"><button id='back' class="btn btn-lg btn-primary small-f right" type="button">Back to Project</button></a>
            <div class="clearfix"></div>
          </form>
         </div>
         <div class="tab-pane" id="holder">
          <form  class='form search'>
            <label for="inputHName" class="sr-only">Name</label>
            <input type="text" id="inputHName" class="form-control medium-f" placeholder="Name" >
            <label for="inputHBarcode" class="sr-only">Barcode</label>
            <input type="text" id="inputHBarcode" class="form-control small-f" placeholder="Barcode" >
            <label for="inputHIRN" class="sr-only">IRN</label>
            <input type="text" id="inputHIRN" class="form-control small-f" placeholder="IRN" >
            <h3 class='small-f N-results'> </h3>
            <button id='holder-search' class="btn btn-lg btn-primary right small-f" type="button"><i class="fa fa-search"></i> Search</button>
            <a href="../edit/"><button id='back' class="btn btn-lg btn-primary small-f right" type="button">Back to Project</button></a>
            <div class="clearfix"></div>
          </form>
          </div>
          <div class="tab-pane" id="group">
            <form class='form search'>
              <label for="inputGName" class="sr-only">Name</label>
              <input type="text" id="inputGName" class="form-control large-f" placeholder="Name" >
              <label for="inputGIRN" class="sr-only">IRN</label>
              <input type="text" id="inputGIRN" class="form-control small-f" placeholder="IRN" >
              <h3 class='small-f N-results'> </h3>
              <p class='small-f'><b>Note:</b> Large groups will take a long time to process but you can leave this page and it will still complete</p>
              <button id='group-search' class="btn btn-lg btn-primary right small-f" type="button"><i class="fa fa-search"></i> Search</button>
              <a href="../edit/"><button id='back' class="btn btn-lg btn-primary small-f right" type="button">Back to Project</button></a>
              <div class="clearfix"></div>
            </form>
           </div>
           <div class="tab-pane" id="event">
             <form class='form search'>
               <label for="inputENumber" class="sr-only">Event Number</label>
               <input type="text" id="inputENumber" class="form-control small-f" placeholder="Event Number" >

               <label for="inputEName" class="sr-only">Name</label>
               <input type="text" id="inputEName" class="form-control medium-f" placeholder="Name" >

               <label for="inputEIRN" class="sr-only">IRN</label>
               <input type="text" id="inputEIRN" class="form-control small-f" placeholder="IRN" >
              <h3 class='small-f N-results'> </h3>
               <p class='small-f'><b>Note:</b> Large events will take a long time to process but you can leave this page and it will still complete</p>
               <button id="event-search" class="btn btn-lg btn-primary right small-f" type="button"><i class="fa fa-search"></i> Search</button>
               <a href="../edit/"><button id='back' class="btn btn-lg btn-primary small-f right" type="button">Back to Project</button></a>
               <div class="clearfix"></div>
             </form>
            </div>
      </div>
    </div>
    <div id="results">
      <table class="table table-striped">
        <colgroup>
           <col span="1" style="width: 1%;">
           <col span="1" style="width: 9%;">
           <col span="1" style="width: 75%;">
           <col span="1" style="width: 10%;">
        </colgroup>
        <thead>
          <tr>
            <th>#</th>
            <th class='center-text'>Image</th>
            <th>Summary</th>
            <th class='center-text'>Add</th>
          </tr>
        </thead>
        <tbody id="result-holder">

        </tbody>
      </table>
    </div>
  </body>
</html>
