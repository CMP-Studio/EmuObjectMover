<?php
require_once __DIR__ . "/config/sqlConfig.php";


function runQuery($query)
{
	$sql = getSQL();

	if($sql->connect_errno)
	{
		return null;
	}

	if($result = $sql->query($query))
	{
		return $result;
	}

	return null;

}

function sqlSafe($data)
{
	$sql = getSQL();
	return $sql->real_escape_string($data);
}

function getSQLerror()
{

}

?>