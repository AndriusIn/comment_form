<?php
function createDatabase($db_server, $db_user, $db_pass, $db_name)
{
	// Create connection
	$conn = mysqli_connect($db_server, $db_user, $db_pass);
	
	// Check connection
	if (!$conn)
	{
		$_SESSION["parent_errors"] .= "<br>" . "Connection failed: " . mysqli_connect_error();
		return 1;
	}

	// Create database
	$sql = "CREATE DATABASE " . $db_name;
	if (!mysqli_query($conn, $sql))
	{
		$_SESSION["parent_errors"] .= "<br>" . "Error creating database:" . "<br>" . mysqli_error($conn);
		return 2;
	}
	
	mysqli_close($conn);
	return 0;
}

function createTable($db_server, $db_user, $db_pass, $db_name, $table_name)
{
	// Create connection
	$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
	
	// Check connection
	if (!$conn)
	{
		$_SESSION["parent_errors"] .= "<br>" . "Connection failed: " . mysqli_connect_error();
		return 1;
	}

	// SQL to create table
	$sql = "CREATE TABLE " . $table_name . " (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	parent_id INT(6) NULL, 
	email VARCHAR(50) NOT NULL, 
	name VARCHAR(50) NOT NULL, 
	comment TEXT NOT NULL, 
	date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	)";

	if (!mysqli_query($conn, $sql))
	{
		$_SESSION["parent_errors"] .= "<br>" . "Error creating table:" . "<br>" . mysqli_error($conn);
		return 2;
	}

	mysqli_close($conn);
	return 0;
}

function emailIsValid($email)
{
	$email = trim($email);
	
	// Returns 1 if email is NULL or an empty string
	if (empty($email)) 
	{
		return 1;
	}
	
	// Return 2 if email is not well-formed
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		return 2;
	}
	
	// Returns 0 if email is valid
	return 0;
}

function nameIsValid($name)
{
	$name = trim($name);
	
	// Returns 1 if name is NULL or an empty string
	if (empty($name)) 
	{
		return 1;
	}
	
	// Return 2 if name is not alphanumeric
	if (!preg_match("/^[\wÀ-ž\s]+$/", $name))
	{
		return 2;
	}
	
	// Returns 0 if name is valid
	return 0;
}

function commentIsValid($comment)
{
	$comment = trim($comment);
	
	// Returns 1 if comment is NULL or an empty string
	if (empty($comment)) 
	{
		return 1;
	}
	
	// Returns 0 if comment is valid
	return 0;
}

function printComments($db_server, $db_user, $db_pass, $db_name, $table_name)
{
	// Create connection
	$conn = mysqli_connect($db_server, $db_user, $db_pass);
	
	// Check connection
	if (!$conn)
	{
		echo '<div class="container">';
		echo '	<div class="row">';
		echo '		<div class="col-sm">';
		echo '			Connection failed: ' . mysqli_connect_error();
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		return;
	}
	
	// Select database
	if (!mysqli_select_db($conn, $db_name))
	{
		echo '<div class="container">';
		echo '	<div class="row">';
		echo '		<div class="col-sm">';
		echo '			<h2>' . 'Comments: 0' . '</h2>';
		echo '		</div>';
		echo '	</div>';
		echo '	<div class="row">';
		echo '		<div class="col-sm">';
		echo '			No comments to display';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		return;
	}
	
	// Check if table does not exist
	if (!mysqli_query($conn, "SELECT * FROM " . $table_name . " LIMIT 1"))
	{
		echo '<div class="container">';
		echo '	<div class="row">';
		echo '		<div class="col-sm">';
		echo '			<h2>' . 'Comments: 0' . '</h2>';
		echo '		</div>';
		echo '	</div>';
		echo '	<div class="row">';
		echo '		<div class="col-sm">';
		echo '			No comments to display';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		return;
	}
	
	// Get comments that are not replies (is not a child comment)
	$sql = "SELECT id, name, date, comment FROM " . $table_name . " WHERE parent_id IS NULL ORDER BY date DESC";
	$result = mysqli_query($conn, $sql);
	
	// Get comment count
	$sql = "SELECT COUNT(id) AS comment_count FROM " . $table_name;
	$count = mysqli_fetch_assoc(mysqli_query($conn, $sql))["comment_count"];
	
	// Print comments
	echo '<div class="container my-2">';
	echo '	<div class="row" id="comment_count">';
	echo '		<div class="col-sm" id="comment_count_content">';
	echo '			<h2>' . 'Comments: ' . $count . '</h2>';
	echo '		</div>';
	echo '	</div>';
	if (mysqli_num_rows($result) > 0)
	{
		while($row = mysqli_fetch_assoc($result))
		{
			// Print parent comment
			echo '	<div class="row">';
			echo '		<div class="col-sm">';
			echo '			<div class="card">';
			echo '				<div class="card-header">';
			echo '					<div class="row">';
			echo '						<div class="col">';
			echo '							<b>' . $row["name"] . '</b>';
			echo '						</div>';
			echo '						<div class="col">';
			echo '							' . $row["date"];
			echo '						</div>';
			echo '						<div class="col-md-auto">';
			echo '							<button class="btn btn-primary rounded toogle-comment" name="reply_button" id="' . $row["id"] . '">Reply</button>';
			echo '						</div>';
			echo '					</div>';
			echo '				</div>';
			echo '				<div class="card-body">';
			echo '					<p style="white-space: pre; display: inline;">' . htmlspecialchars($row["comment"]) . '</p>';
			echo '				</div>';
			echo '			</div>';
			echo '		</div>';
			echo '	</div>';
			
			// Print reply form
			echo '	<div id="load_child_form_' . $row["id"] . '">';
			echo '		<div id="load_child_form_content_' . $row["id"] . '">';
			if (isset($_SESSION["child_errors_" . $row["id"]]))
			{
				echo '			<div class="row" id="child_form_' . $row["id"] . '">';
			}
			else
			{
				echo '			<div class="row" id="child_form_' . $row["id"] . '" style="display: none;">';
			}
			echo '				<div class="col-sm-1">';
			echo '				</div>';
			echo '				<div class="col-sm px-0">';
			echo '					<form id="child_form_submit_' . $row["id"] . '" class="child_form_submit" value="' . $row["id"] . '">';
			echo '						<input type="hidden" id="parent_id" name="parent_id" value="' . $row["id"] . '">';
			echo '						<div class="container my-2">';
			echo '							<div class="p-2 border border-primary rounded">';
			echo '								<div class="row">';
			echo '									<div class="col-sm">';
			echo '										<label for="child_email"><b>Email</b></label>';
			if (isset($_SESSION["child_email_" . $row["id"]]))
			{
				echo '										<input type="text" class="form-control rounded" id="child_email" name="child_email" value="' . $_SESSION["child_email_" . $row["id"]] . '">';
				unset($_SESSION["child_email_" . $row["id"]]);
			}
			else
			{
				echo '										<input type="text" class="form-control rounded" id="child_email" name="child_email">';
			}
			echo '									</div>';
			echo '								</div>';
			echo '								<div class="row">';
			echo '									<div class="col-sm">';
			echo '										<label for="child_name"><b>Name</b></label>';
			if (isset($_SESSION["child_name_" . $row["id"]]))
			{
				echo '										<input type="text" class="form-control rounded" id="child_name" name="child_name" value="' . $_SESSION["child_name_" . $row["id"]] . '">';
				unset($_SESSION["child_name_" . $row["id"]]);
			}
			else
			{
				echo '										<input type="text" class="form-control rounded" id="child_name" name="child_name">';
			}
			echo '									</div>';
			echo '								</div>';
			echo '								<div class="row">';
			echo '									<div class="col-sm">';
			echo '										<label for="child_comment"><b>Comment</b></label>';
			if (isset($_SESSION["child_comment_" . $row["id"]]))
			{
				echo '										<textarea class="form-control rounded" id="child_comment" name="child_comment">' . $_SESSION["child_comment_" . $row["id"]] . '</textarea>';
				unset($_SESSION["child_comment_" . $row["id"]]);
			}
			else
			{
				echo '										<textarea class="form-control rounded" id="child_comment" name="child_comment"></textarea>';
			}
			echo '									</div>';
			echo '								</div>';
			echo '								<div class="row" id="child_error_' . $row["id"] . '">';
			echo '									<div class="col-sm" id="child_error_content_' . $row["id"] . '">';
			// Prints message if errors were found
			if (isset($_SESSION["child_errors_" . $row["id"]]))
			{
				echo '										' . $_SESSION["child_errors_" . $row["id"]];
				unset($_SESSION["child_errors_" . $row["id"]]);
			}
			echo '									</div>';
			echo '								</div>';
			echo '								<div class="row">';
			echo '									<div class="col-sm pt-2">';
			echo '										<button class="btn btn-primary rounded" type="submit">Submit</button>';
			echo '									</div>';
			echo '								</div>';
			echo '							</div>';
			echo '						</div>';
			echo '					</form>';
			echo '				</div>';
			echo '			</div>';
			echo '		</div>';
			echo '	</div>';
			
			// Get replies (child comments)
			$sql = "SELECT name, date, comment FROM " . $table_name . " WHERE parent_id = " . $row["id"] . " ORDER BY date DESC";
			$child_result = mysqli_query($conn, $sql);
			
			echo '	<div id="load_child_comments_' . $row["id"] . '">';
			echo '		<div id="load_child_comments_content_' . $row["id"] . '">';
			// Check if comment has replies (child comments)
			if (mysqli_num_rows($child_result) > 0)
			{
				// Print replies (child comments)
				while($child_row = mysqli_fetch_assoc($child_result))
				{
					echo '			<div class="row">';
					echo '				<div class="col-sm-1">';
					echo '				</div>';
					echo '				<div class="col-sm">';
					echo '					<div class="card">';
					echo '						<div class="card-header">';
					echo '							<div class="row">';
					echo '								<div class="col-md-auto">';
					echo '									<b>' . $child_row["name"] . '</b>';
					echo '								</div>';
					echo '								<div class="col">';
					echo '									' . $child_row["date"];
					echo '								</div>';
					echo '							</div>';
					echo '						</div>';
					echo '						<div class="card-body">';
					echo '							<p style="white-space: pre; display: inline;">' . htmlspecialchars($child_row["comment"]) . '</p>';
					echo '						</div>';
					echo '					</div>';
					echo '				</div>';
					echo '			</div>';
				}
			}
			echo '		</div>';
			echo '	</div>';
		}
	}
	else
	{
		echo '	<div class="row">';
		echo '		<div class="col-sm">';
		echo '			No comments to display';
		echo '		</div>';
		echo '	</div>';
	}
	echo '</div>';

	mysqli_close($conn);
}
?>