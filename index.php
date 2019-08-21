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
				$('form[class="child_form_submit"]').on('submit', function (e) {
					e.preventDefault();
					$.ajax({
						type: 'post', 
						url: 'create_child_comment.php', 
						data: $('#' + e.target.id).serialize(), 
						success: function () {
							// Reload comments
							$("#print_comments").load(" #comment_content");
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
		<form id="parent_form">
			<div class="my-2 container">
				<div class="p-2 border border-primary rounded">
					<div class="row">
						<div class="col-sm">
							<div class="input-group input-group-lg mb-2">
								<div class="input-group-prepend">
									<span class="input-group-text" style="width: 120px;">Email</span>
								</div>
								<input type="text" class="form-control rounded" id="email" name="email">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm">
							<div class="input-group input-group-lg mb-2">
								<div class="input-group-prepend">
									<span class="input-group-text" style="width: 120px;">Name</span>
								</div>
								<input type="text" class="form-control rounded" id="name" name="name">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm">
							<div class="input-group input-group-lg">
								<div class="input-group-prepend">
									<span class="input-group-text" style="width: 120px;">Comment</span>
								</div>
								<textarea class="form-control rounded" id="comment" name="comment"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm">
							<button class="btn btn-primary rounded mt-2" type="submit" style="width: 120px;">Submit</button>
						</div>
					</div>
				</div>
			</div>
		</form>
		
		<!-- Print comments -->
		<div id="print_comments">
			<div id="comment_content">
				<?php
				// Prints message if errors were found
				if (isset($_SESSION["errors"]))
				{
					echo '<div class="container">';
					echo '	<div class="row">';
					echo '		<div class="col-sm">';
					echo '			' . $_SESSION["errors"];
					echo '		</div>';
					echo '	</div>';
					echo '</div>';
					
					session_unset();
				}
				
				printComments(DB_SERVER, DB_USER, DB_PASS, DB_NAME, TBL_COMMENT);
				?>
			</div>
		</div>
	</body>
</html>