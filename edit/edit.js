 var project = null;

  function setProject(p)
  {
    project = p;
  }


$(document).ready(function() 
{
	loadItems();
	function loadItems()
	{
		var url = 'editAjax.php?action=get&project=' + project;

		$.getJSON(url).done( function(data)
		{
			console.log(data);
			var base = "#object-body";
			for (var i = data.objects.length - 1; i >= 0; i--) {
				var e = data.objects[i];

				var html = "<tr>\n<td class='center-text'>\n\t";
				if(e.image_url)
				{
					html += "<img src='" + e.image_url + "'></img>";
				}
				else
				{
					html += "No Image";
				}
				html += "\n</td>\n<td>\n\t";
				html += e.accession_no;
				html += "\n</td>\n<td>\n\t";
				html += e.title;
				html += "\n</td>\n<td class='center-text'>\n\t";
				html += "<button type=\"button\" class=\"btn btn-lg btn-danger delBtn\" data-irn='" + e.irn  + "'><i class=\"fa fa-trash-o\"></i></button>";
				html += "\n</td>\n</tr>";

				$(base).append(html);


			};
			delClick();
		});
	}

	function delClick()
	{
		$(".delBtn").unbind("click");
		$(".delBtn").click(function() {
			var irn = $(this).attr("data-irn");
			var url = 'editAjax.php?action=delete&project=' + project +'&irn=' + irn;
			console.log(url);
			$.getJSON(url).done( function(data)
			{
				console.log(data);
				$(this).parent().parent().remove();
			});

		});

	}
});