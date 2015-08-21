<?php
require_once __DIR__ . "/config/sqlConfig.php";

$errors = array();
$lastID = null;

/* Insert / Update / Delete -  returns true on success or false on failure */
function writeQuery($query)
{
	global $lastID;

	$sql = getSQL();

	if($sql->connect_errno)
	{
		recConnError($sql);
		return false;
	}

	if($sql->query($query))
	{
		if(isset($sql->insert_id))
		{
			$lastID = $sql->insert_id;
		}
		return true;
	}

	recSQLerror($sql, $query);
	return false;
}

/* Select -  returns data on success or null on failure */
function readQuery($query)
{
	$sql = getSQL();



	if($sql->connect_errno)
	{
		recConnError($sql);
		return null;
	}

	if($result = $sql->query($query))
	{
		return $result;
	}

	recSQLerror($sql, $query);
	return null;
}

function sqlSafe($data)
{
	$sql = getSQL();
	if(!isset($data)) return 'NULL';
	return "'" . $sql->real_escape_string($data) . "'"; 
}

function recConnError($sql)
{
	global $errors;

	$err = array();
	$err['source'] = 'SQL: connection error';
	$err['errno'] = $mysqli->connect_errno;
	$err['error'] = $mysqli->connect_error;

	array_push($errors, $err);
}

function recSQLerror($sql, $query)
{
	global $errors;
	$err = array();
	$err['source'] = 'SQL: query error';
	$err['errno'] = $sql->errno;
	$err['error'] = $sql->error;
	$err['query'] = $query;

	array_push($errors, $err);
}

function getSQLerrors()
{
	global $errors;
	return $errors;
}

function hasSQLerrors()
{
	global $errors;
	if(count($errors) > 0) return true;

	return false;
}

function clearSQLerrors()
{
	global $errors;
	$errors = array();
}
function getInsertID()
{
	global $lastID;
	return $lastID;
}

function tryRetrieve($array, $key)
{
	if(isset($array[$key]))
	{
		return $array[$key];
	}
	else
	{
		return null;
	}
} 

?>