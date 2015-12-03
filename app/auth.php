<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/sql.php";

$timeout = 5*60*60;

function authorize($email)
{
	$query = "SELECT * FROM accounts WHERE email = " . sqlSafe($email);

	$result = readQuery($query);
	while ($row = $result->fetch_assoc())
	{
		$_SESSION["auth_account_id"] = $row["id"];
		$_SESSION["auth_active_time"] = date("Y-m-d H:i:s");
		$_SESSION["auth_valid"] = TRUE;
		return true;
	}
	$_SESSION["auth_invalid_reason"] = "That is not an account, please register first";
	return false;
}
function createAccount($email, $name)
{
	$query = "INSERT INTO accounts (`email`, `fullname`) VALUES (" . sqlSafe($email) . ", " . sqlSafe($name) . ")";
	if(writeQuery($query))
	{
		authorize($email);
		return true;
	}
	else
	{
		var_dump(getSQLerrors());
		return false;
	}

}

function checkAuth()
{

	global $timeout;
	//The great if tree of authorization checks
	if(isset($_SESSION["auth_valid"]))
	{
		if($_SESSION["auth_valid"] == TRUE)
		{
			if(isset($_SESSION["auth_active_time"]))
			{
				$timeS = strtotime($_SESSION["auth_active_time"]);
				$timeC = time();
				if($timeC - $timeS < $timeout)
				{
					//All good!

					//Update activity timer
					$_SESSION["auth_active_time"] = date("Y-m-d H:i:s");
					//And return
					return true;
				}
				else
				{
					$_SESSION["auth_invalid_reason"] = "You're session has expired, please log back in";
				}
			}
			else
			{
				$_SESSION["auth_invalid_reason"] = "You are logged out, please sign in first";
			}
		}
		else
		{
			$_SESSION["auth_invalid_reason"] = "You are logged out, please sign in first";
		}
	}
	else
	{
		$_SESSION["auth_invalid_reason"] = "You are logged out, please sign in first";
	}
	//For some reason the auth has been rejected, return to homepage
	return false;
}
function deauthorize()
{
	if(isset($_SESSION["auth_valid"]))
	{
		$_SESSION["auth_valid"] = FALSE;
	}
}

function getAccount()
{
	if(checkAuth())
			return $_SESSION["auth_account_id"];

	return null;
}

function getInvalidReason()
{
	if(isset($_SESSION["auth_invalid_reason"]))
	{
		$reason = $_SESSION["auth_invalid_reason"];
		unset($_SESSION["auth_invalid_reason"]);
		return $reason;
	}

	return null;
}



?>
