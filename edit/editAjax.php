<?php
/* Handles calls from the edit page.

		get - Gets items from project
		delete - Delets an itme from the project

*/
getItems();

function getItems()
{
	$project = $_GET['project'];
	$query = "SELECT o.irn, o.image_url,  o.accession_no, o.title FROM objectProject op 
	 LEFT JOIN objects o ON (op.object_irn = o.irn)
	 WHERE op.project_id = " . sqlSafe($project);

	$res = readQuery($query);
}




?>