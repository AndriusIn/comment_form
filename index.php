<?php
include("include/settings.php");
include("include/functions.php");

session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Comment Form</title>
        <link rel="stylesheet" href="https://bootswatch.com/4/cosmo/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		
		<!-- Ajax requests -->
		<script>
			// Submit parent comment
			$(function (){
				$('#parent_form').on('submit', function (e){
					e.preventDefault();
					$.ajax({
						type: 'post', 
						url: 'create_comment.php', 
						data: $('#parent_form').serialize(), 
						success: function () {
							// Load errors
							$("#parent_error").load(" #parent_error_content");
							// Reload comments
							$("#print_comments").load(" #comment_content");
						}
					});
				});
			});
			// Hide or show reply form
			$(function () {
				$(document).on('click', '.btn.btn-primary.rounded.toogle-comment', function (e) {
					$.ajax({
						success: function () {
							$("#child_form_" + e.target.id).toggle();
						}
					});
				});
			});
			// Submit child comment
			$(function () {
				$(document).on('submit', '.child_form_submit', function (e) {
					e.preventDefault();
					$.ajax({
						type: 'post', 
						url: 'create_child_comment.php', 
						data: $('#' + e.target.id).serialize(), 
						success: function () {
							// Reload reply form
							$("#load_child_form_" + $('#' + e.target.id).attr("value")).load(" #load_child_form_content_" + $('#' + e.target.id).attr("value"));
							// Reload child comments
							$("#load_child_comments_" + $('#' + e.target.id).attr("value")).load(" #load_child_comments_content_" + $('#' + e.target.id).attr("value"));
							// Reload comment count
							$("#comment_count").load(" #comment_count_content");
						}
					});
				});
			});
		</script>
    </head>
	<body>
		<!-- Page header -->
		<div class="container">
			<div class="my-2 border-top border-bottom border-dark">
				<div class="text-center">
					<a href="index.php" class="h1">Comment Form</a>
				</div>
			</div>
		</div>
		
		<!-- Comment form -->
		<form id="parent_form" class="needs-validation" novalidate>
			<div class="my-2 container">
				<div class="p-2 border border-primary rounded">
					<div class="row">
						<div class="col-sm">
							<label for="email"><b>Email</b></label>
							<input type="text" class="form-control rounded" id="email" name="email">
						</div>
					</div>
					<div class="row">
						<div class="col-sm">
							<label for="name"><b>Name</b></label>
							<input type="text" class="form-control rounded" id="name" name="name">
						</div>
					</div>
					<div class="row">
						<div class="col-sm">
							<label for="comment"><b>Comment</b></label>
							<textarea class="form-control rounded" id="comment" name="comment"></textarea>
						</div>
					</div>
					<div class="row" id="parent_error">
						<div class="col-sm" id="parent_error_content">
							<?php
							// Prints message if errors were found
							if (isset($_SESSION["parent_errors"]))
							{
								echo $_SESSION["parent_errors"];
								unset($_SESSION['parent_errors']);
							}
							?>
						</div>
					</div>
					<div class="row">
						<div class="col-sm pt-2">
							<button class="btn btn-primary rounded" type="submit">Submit</button>
						</div>
					</div>
				</div>
			</div>
		</form>
		
		<!-- Print comments -->
		<div id="print_comments">
			<div id="comment_content">
				<?php
				printComments(DB_SERVER, DB_USER, DB_PASS, DB_NAME, TBL_COMMENT);
				?>
			</div>
		</div>
	</body>
</html>