<?php
include("include/settings.php");
include("include/functions.php");

session_start();

$_SESSION["parent_errors"] = '<p style="display: inline; color: red;">' . "Errors:";

$email = trim($_POST['email']);
$name = trim($_POST['name']);
$comment = $_POST['comment'];

$commentIsValid = true;

$emailValidation = emailIsValid($email);
$nameValidation = nameIsValid($name);
$commentValidation = commentIsValid($comment);

switch ($emailValidation)
{
	case 1:
		$commentIsValid = false;
		$_SESSION["parent_errors"] .= "<br>" . "Email is empty!";
		break;
	case 2:
		$commentIsValid = false;
		$_SESSION["parent_errors"] .= "<br>" . "Email format is invalid!";
		break;
	default:
		break;
}

switch ($nameValidation)
{
	case 1:
		$commentIsValid = false;
		$_SESSION["parent_errors"] .= "<br>" . "Name is empty!";
		break;
	case 2:
		$commentIsValid = false;
		$_SESSION["parent_errors"] .= "<br>" . "Name is not alphanumeric!";
		break;
	default:
		break;
}

switch ($commentValidation)
{
	case 1:
		$commentIsValid = false;
		$_SESSION["parent_errors"] .= "<br>" . "Comment is empty!";
		break;
	default:
		break;
}

if ($commentIsValid)
{
	// Creates database if it doesn't exist
	if (!mysqli_select_db(mysqli_connect(DB_SERVER, DB_USER, DB_PASS), DB_NAME))
	{
		if (createDatabase(DB_SERVER, DB_USER, DB_PASS, DB_NAME) !== 0)
		{
			$_SESSION["parent_errors"] .= "<br>" . "Submission failed!" . "</p>";
			return;
		}
	}
	
	// Creates table if it doesn't exist
	if (!mysqli_query(mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME), "SELECT * FROM " . TBL_COMMENT . " LIMIT 1"))
	{
		if (createTable(DB_SERVER, DB_USER, DB_PASS, DB_NAME, TBL_COMMENT) !== 0)
		{
			$_SESSION["parent_errors"] .= "<br>" . "Submission failed!" . "</p>";
			return;
		}
	}
	
	// Create connection
	$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	
	// Check connection
	if (!$db)
	{
		$_SESSION["parent_errors"] .= "<br>" . "Connection failed: " . mysqli_connect_error();
		$_SESSION["parent_errors"] .= "<br>" . "Submission failed!" . "</p>";
		return;
	}
	
	// Escapes special characters in a comment string
	$comment = mysqli_real_escape_string($db, $comment);
	
	// Inserts comment into database
	$sql = "INSERT INTO " . TBL_COMMENT . " (email, name, comment) VALUES ('$email', '$name', '$comment')";
	if (!mysqli_query($db, $sql))
	{
		$_SESSION["parent_errors"] .= "<br>" . "Failed to insert comment into database:" . "<br>" . mysqli_error($db);
		$_SESSION["parent_errors"] .= "<br>" . "Submission failed!" . "</p>";
		return;
	}
	else
	{
		unset($_SESSION['parent_errors']);
	}
}
else
{
	$_SESSION["parent_errors"] .= "<br>" . "Submission failed!" . "</p>";
}
?>