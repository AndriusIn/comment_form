<?php
include("include/settings.php");
include("include/functions.php");

session_start();

$parent_id = $_POST['parent_id'];
$email = trim($_POST['child_email']);
$name = trim($_POST['child_name']);
$comment = trim($_POST['child_comment']);

$_SESSION["child_errors_" . $parent_id] = '<p style="display: inline; color: red;">' . "Errors:";

$commentIsValid = true;

$emailValidation = emailIsValid($email);
$nameValidation = nameIsValid($name);
$commentValidation = commentIsValid($comment);

switch ($emailValidation)
{
	case 1:
		$commentIsValid = false;
		$_SESSION["child_errors_" . $parent_id] .= "<br>" . "Email is empty!";
		break;
	case 2:
		$commentIsValid = false;
		$_SESSION["child_errors_" . $parent_id] .= "<br>" . "Email format is invalid!";
		break;
	default:
		break;
}

switch ($nameValidation)
{
	case 1:
		$commentIsValid = false;
		$_SESSION["child_errors_" . $parent_id] .= "<br>" . "Name is empty!";
		break;
	default:
		break;
}

switch ($commentValidation)
{
	case 1:
		$commentIsValid = false;
		$_SESSION["child_errors_" . $parent_id] .= "<br>" . "Comment is empty!";
		break;
	default:
		break;
}

if ($commentIsValid)
{
	// Creates database and tables if database doesn't exist
	if (!mysqli_select_db(mysqli_connect(DB_SERVER, DB_USER, DB_PASS), DB_NAME))
	{
		createDatabase(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		createTable(DB_SERVER, DB_USER, DB_PASS, DB_NAME, TBL_COMMENT);
	}
	
	// Create connection
	$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	
	// Inserts comment into database
	$sql = "INSERT INTO " . TBL_COMMENT . " (parent_id, email, name, comment) VALUES ('$parent_id', '$email', '$name', '$comment')";
	if (!mysqli_query($db, $sql))
	{
		$_SESSION["child_errors_" . $parent_id] .= "<br>" . "Failed to insert comment into database:" . "<br>" . mysqli_error($db) . "</p>";
	}
	else
	{
		unset($_SESSION["child_errors_" . $parent_id]);
	}
}
else
{
	$_SESSION["child_errors_" . $parent_id] .= "<br>" . "Submission failed!" . "</p>";
}
?>