$( document ).ready(function (){

	$('.delete-project').click(function()
	{
		var target = $(this).attr('data-target');
		$(this).parent().parent().after(
			"<tr class='del-row'><td colspan='4' class='right-text' >Are you sure you want to delete this project?</td><td><form method='POST'><input type='hidden' name='projectToDelete' value='" + target + "'><button type='submit' class='btn btn-large btn-success btn-delete-yes'>Yes</button></form></td><td><button class='btn btn-large btn-danger btn-delete-no' data-target='" + target + "'>No</button></td></tr>"
		);
		$(this).prop('disabled', true);
		newButton(target);
	});
	function newButton(target)
	{
		$(".btn-delete-no[data-target='" + target + "']").click(function()
		{
			$(".delete-project[data-target='" + target + "']").prop('disabled', false);
			$(this).parent().parent().remove();
		});
	}

});