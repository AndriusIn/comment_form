<?php
include("include/settings.php");
include("include/functions.php");

session_start();

$parent_id = $_POST['parent_id'];
$email = trim($_POST['child_email']);
$name = trim($_POST['child_name']);
$comment = $_POST['child_comment'];

$child_email_session_name = "child_email_" . $parent_id;
$child_name_session_name = "child_name_" . $parent_id;
$child_comment_session_name = "child_comment_" . $parent_id;
$child_errors_session_name = "child_errors_" . $parent_id;

$_SESSION[$child_email_session_name] = "";
$_SESSION[$child_name_session_name] = "";
$_SESSION[$child_comment_session_name] = "";
$_SESSION[$child_errors_session_name] = '<p style="display: inline; color: red;">' . "Errors:";

$commentIsValid = true;

$emailValidation = emailIsValid($email);
$nameValidation = nameIsValid($name);
$commentValidation = commentIsValid($comment);

switch ($emailValidation)
{
	case 1:
		$commentIsValid = false;
		$_SESSION[$child_errors_session_name] .= "<br>" . "Email is empty!";
		unset($_SESSION[$child_email_session_name]);
		break;
	case 2:
		$commentIsValid = false;
		$_SESSION[$child_errors_session_name] .= "<br>" . "Email format is invalid!";
		$_SESSION[$child_email_session_name] = $email;
		break;
	default:
		$_SESSION[$child_email_session_name] = $email;
		break;
}

switch ($nameValidation)
{
	case 1:
		$commentIsValid = false;
		$_SESSION[$child_errors_session_name] .= "<br>" . "Name is empty!";
		unset($_SESSION[$child_name_session_name]);
		break;
	case 2:
		$commentIsValid = false;
		$_SESSION[$child_errors_session_name] .= "<br>" . "Name is not alphanumeric!";
		$_SESSION[$child_name_session_name] = $name;
		break;
	default:
		$_SESSION[$child_name_session_name] = $name;
		break;
}

switch ($commentValidation)
{
	case 1:
		$commentIsValid = false;
		$_SESSION[$child_errors_session_name] .= "<br>" . "Comment is empty!";
		unset($_SESSION[$child_comment_session_name]);
		break;
	default:
		$_SESSION[$child_comment_session_name] = $comment;
		break;
}

if ($commentIsValid)
{
	// Create connection
	$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	
	// Check connection
	if (!$db)
	{
		$_SESSION[$child_errors_session_name] .= "<br>" . "Connection failed: " . mysqli_connect_error();
		$_SESSION[$child_errors_session_name] .= "<br>" . "Submission failed!" . "</p>";
		return;
	}
	
	// Inserts comment into database
	$sql = "INSERT INTO " . TBL_COMMENT . " (parent_id, email, name, comment) VALUES ('$parent_id', '$email', '$name', '$comment')";
	if (!mysqli_query($db, $sql))
	{
		$_SESSION[$child_errors_session_name] .= "<br>" . "Failed to insert comment into database:" . "<br>" . mysqli_error($db) . "</p>";
		$_SESSION[$child_errors_session_name] .= "<br>" . "Submission failed!" . "</p>";
		return;
	}
	else
	{
		unset($_SESSION[$child_email_session_name]);
		unset($_SESSION[$child_name_session_name]);
		unset($_SESSION[$child_comment_session_name]);
		unset($_SESSION[$child_errors_session_name]);
	}
}
else
{
	$_SESSION[$child_errors_session_name] .= "<br>" . "Submission failed!" . "</p>";
}
?>