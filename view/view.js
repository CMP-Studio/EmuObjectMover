


$(document).ready(function()
{
	/* 'Main' functions */
	inlineEdit();
	loadItems();
	$("#btnSD").click(genSD);


	/* Load Items */

	function loadItems()
	{
		var url = 'viewAjax.php?action=get';

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

	/* Delete items */
	function delClick()
	{
		$(".delBtn").unbind("click");
		$(".delBtn").click(function() {
			var irn = $(this).attr("data-irn");
			var url = 'viewAjax.php?action=delete&irn=' + irn;
			console.log(url);
			$.getJSON(url).done( function(data)
			{
				console.log(data);
				$(".delBtn[data-irn=" + irn +"]").parent().parent().remove();
			});

		});

	}

	function inlineEdit()
	{
		/* Editing details */

		/* Create a cancel button */
		$(".edit-btn").each(function(index, element)
		{
			var input = $(this).attr("input-target");
			var display = $(this).attr("display-target");
			var edit = $(this).attr("id");
			var me = edit + '-cancel';

			$(this).parent().append("<button id='" + me + "' class='cancel-edit nobutton'><i class='fa fa-times'></i>");
			$('#' + me).attr("input-target",input).attr("display-target",display).attr("edit-target", '#' + edit);
			$(this).attr("cancel-target", '#' + me);
		});


		$(".edit-control").hide();
		$(".cancel-edit").hide();

		$(".edit-btn").click(function () {
			if($(this).attr("toggle") == "edit")
			{
				var input = $(this).attr("input-target");
				var display = $(this).attr("display-target");
				var cancel = $(this).attr("cancel-target");
				var key = $(this).attr("data-key");


				var text = $(display).text();

				if(text == "Saving...")
				{
					return;
				}
				else if(text != "None" && text)
				{
					$(input).val(text.trim());
				}
				$(display).hide();
				$(input).show();

				$(this).children().filter("i").removeClass();
				$(this).children().filter("i").addClass("fa");
				$(this).children().filter("i").addClass("fa-floppy-o");

				//Setup cancel button
				$(cancel).show();

				$(this).attr("toggle","save");
			}
			else
			{

				var input = $(this).attr("input-target");
				var display = $(this).attr("display-target");
				var cancel = $(this).attr("cancel-target");

				var value = $(input).val().trim();
				var key = $(this).attr("data-key");

				$(input).hide();


				$(display).text("Saving...");
				updateProject(key, value);

				$(display).show();



				$(this).children().filter("i").removeClass();
				$(this).children().filter("i").addClass("fa");
				$(this).children().filter("i").addClass("fa-pencil");


				$(cancel).hide();
				$(this).attr("toggle","edit");
			}
		});

		$(".cancel-edit").click(function()
		{
			var input = $(this).attr("input-target");
			var display = $(this).attr("display-target");
			var edit = $(this).attr("edit-target");

			$(input).val('');
			$(input).hide();
			$(display).show();
			$(this).hide();

			$(edit).children().filter("i").removeClass();
			$(edit).children().filter("i").addClass("fa");
			$(edit).children().filter("i").addClass("fa-pencil");
			$(edit).attr("toggle","edit");
		});
	}

	function updateProject(key, val)
	{
		var url = 'viewAjax.php?action=update&key=' + encodeURIComponent(key) + "&value=" + encodeURIComponent(val);
		console.log(url);
		$.getJSON(url).done(function(data)
		{
			var good = data.success;
			if(good)
			{
				console.log(data);


				$($("button[data-key=\"" + key +"\"]").attr("display-target")).text(val);

			}

		});
	}



	function genSD()
	{
		var url = 'viewAjax.php?action=servicedesk';
		console.log(url);
		$.getJSON(url).done(function(data)
		{
			var url = data.url;
			$("#projButtons").append("<a target='_blank' href='" + url + "'>ServiceDesk Project</a>");
			$("#btnSD").hide();
		});
	}




});
