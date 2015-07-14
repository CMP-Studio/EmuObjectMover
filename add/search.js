$(document).ready(function() {

  var respage = 0;
  var type=null;
  var scrollpage = true;

  $("#single-object").click(function(){
    $("form.search button").attr("disabled","disabled");
      $("#result-holder").empty();
      singleSearch(0);
      type = 'single';
  });

  $("#holder-search").click(function(){
    $("form.search button").attr("disabled","disabled");
      $("#result-holder").empty();
      holderSearch(0);
      type = 'holder';
  });

  $("#group-search").click(function(){
    $("form.search button").attr("disabled","disabled");
      $("#result-holder").empty();
      groupSearch(0);
      type = 'group';
  });

  $("#event-search").click(function(){
    $("form.search button").attr("disabled","disabled");
      $("#result-holder").empty();
      eventSearch(0);
      type = 'event';
  });



  $(window).scroll(function() {

    //document.title = "S: " + $(window).scrollTop() + " W: " + $(window).height() + " D: " + $(document).height();

    if($(window).scrollTop() + $(window).height() == $(document).height())
     {

          if(type)
          {

            respage++;
            setTimeout(function() {

              if(type == 'single')
              {
                singleSearch(respage);
              }
              else if (type=='holder')
              {
                holderSearch(respage);
              }
              else if (type=='group')
              {
                groupSearch(respage);
              }
              else if(type=='event')
              {
                eventSearch(respage);
              }
              else {
                //do nothing
              }

            }, 500);


          }

     }
 });

  function singleSearch(page)
  {
    var url = "searchAjax.php?m=single";

    if($("#inputID").val())
    {
      url += "&accnum=" + $("#inputID").val();
    }
    if($("#inputTitle").val())
    {
      url += "&title=" + $("#inputTitle").val();
    }
    if($("#inputCreator").val())
    {
      url += "&creator=" + $("#inputCreator").val();
    }
    if($("#inputSBarcode").val())
    {
      url += "&barcode=" + $("#inputSBarcode").val();
    }
    if($("#inputSIRN").val())
    {
      url += "&irn=" + $("#inputSIRN").val().toString();
    }
    if(page > 0)
    {
      url += "&start=" + (15 * page);
    }

    APIsearch(url);

  }


  function holderSearch(page)
  {
      var url = "searchAjax.php?m=holder";

      if($("#inputHName").val())
      {
        url += "&name=" + $("#inputHName").val();
      }
      if($("#inputHBarcode").val())
      {
        url += "&barcode=" + $("#inputHBarcode").val();
      }
      if($("#inputHIRN").val())
      {
        url += "&irn=" + $("#inputHIRN").val().toString();
      }
      if(page > 0)
      {
        url += "&start=" + (15 * page);
      }

    APIsearch(url);

  }

  function groupSearch(page)
  {
    var url = "searchAjax.php?m=group";

    if($("#inputGName").val())
    {
      url += "&name=" + $("#inputGName").val();
    }
    if($("#inputGIRN").val())
    {
      url += "&irn=" + $("#inputGIRN").val().toString();
    }
    if(page > 0)
    {
      url += "&start=" + (15 * page);
    }

    APIsearch(url);

  }
  function eventSearch(page)
  {
    var url = "searchAjax.php?m=event";

    if($("#inputEName").val())
    {
      url += "&name=" + $("#inputEName").val();
    }
    if($("#inputENumber").val())
    {
      url += "&evnum=" + $("#inputENumber").val().toString();
    }
    if($("#inputEIRN").val())
    {
      url += "&irn=" + $("#inputEIRN").val().toString();
    }

    if(page > 0)
    {
      url += "&start=" + (15 * page);
    }

    APIsearch(url);

  }

  function APIsearch(url)
  {
    console.log(url);

    $.getJSON(url).done(function (data) {
      $("form.search button").attr("disabled",null);
      $('.N-results').text(data.hits + " Results");
      console.log(data);
      dispObjects(data);
    })
    .fail(function( jqxhr, textStatus, error){
      var err = textStatus + ", " + error;
      console.log("Fail: " + err);
      $("form.search button").attr("disabled",null);
    });
  }

  function dispObjects(objects)
  {
    if (objects.rows == null)
    {
      return;
    }
    var len = objects.rows.length;
    for(var i = 0; i < len; i++ ) {
      var pic = objects.rows[i].image;
      var summary = objects.rows[i].SummaryData;
      var row = objects.rows[i].rownum;
      var irn = objects.rows[i].irn;

      var img;
      if(pic)
      {
        img = "<img  src='" + pic +  "'>";
      }
      else {
        img = "No Image";
      }

      $("#result-holder").append("<tr><td>" + row + "</td><td class='center-text'>" + img + "</td><td>" + summary + "</td><td class='center-text'><button id='addItem' class='btn btn-success btn-lg' type='button' data-irn='" + irn + "'><i class='fa fa-plus'></i></button></td>");
    }
  }



});
