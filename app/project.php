<?php
require_once __DIR__ . '/../config.php';
	require_once filepath() . "app/sql.php";

	function getProjectInfo()
	{
		$query = "SELECT p.id, p.duedate, p.title, p.notes, p.hash, p.moveto FROM projects p WHERE p.id = " . sqlSafe($_SESSION['project']);

		$result = readQuery($query);


		$return = array();
		while($row = $result->fetch_assoc())
		{
			$return = $row;
		}

		return $return;

	}

	function createProject()
	{
		$account = 1; //For now

		$duedate = sqlSafe(tryRetrieve($_POST, 'projDue'));
		$title = sqlSafe(tryRetrieve($_POST, 'projName'));
		$notes = sqlSafe(tryRetrieve($_POST, 'projNotes'));


		$query = "INSERT INTO projects (account_id, duedate, title, notes) VALUES ($account, $duedate, $title, $notes)";
		if(writeQuery($query))
		{
			$id = getInsertID();

			//Now give the project a hash
			$hash = sqlSafe(hash('adler32', $id));
			$query = "UPDATE projects SET hash=$hash where id='$id'";

			if(writeQuery($query))
			{

				$_SESSION['project'] = $id;

				return getProjectInfo();
			}
		}
		return null;

	}


?>