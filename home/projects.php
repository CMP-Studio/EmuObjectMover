<?php
	require_once '../config.php';
	require_once filepath() . "app/sql.php";

	function getProjects($account)
	{
		$query = "SELECT p.id, p.duedate, p.title, p.notes, (select count(o.id) from objectProject o where p.id = o.project_id group by o.project_id) as nObjects  FROM projects p WHERE account_id = " . sqlSafe($account);

		$result = readQuery($query);


		$return = array();
		while($row = $result->fetch_assoc())
		{
			array_push($return, $row);
		}

		return $return;
	}

	function deleteProject($project)
	{
		// To make this more secure check the account in the session field to ensure the account has access to the project
		$query = "DELETE FROM projects WHERE id=" . sqlSafe($project);
		writeQuery($query);
	}



?>