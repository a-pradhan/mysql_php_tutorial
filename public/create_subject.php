<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php require_once("../includes/validation_functions.php"); ?>

<?php
if (isset($_POST['submit'])) {
	// Process the form
	
	$menu_name = mysql_prep($_POST["menu_name"]);
	$position = (int) $_POST["position"];
	$visible = (int) $_POST["visible"];
	
	// validations
	$required_fields = array("menu_name", "position", "visible");
	validate_presences($required_fields);
	
	$fields_with_max_lengths = array("menu_name" => 30);
	validate_max_lengths($fields_with_max_lengths);
	
	if (!empty($errors)) {
		$_SESSION["errors"] = $errors;
		redirect_to("new_subject.php");
	}
	
	$query  = "INSERT INTO subjects (";
	$query .= "  menu_name, position, visible";
	$query .= ") VALUES (";
	$query .= "  '{$menu_name}', {$position}, {$visible}";
	$query .= ")";
	$result = mysqli_query($connection, $query);

	if ($result) {
		// Success
		$_SESSION["message"] = "Subject created.";
		redirect_to("manage_content.php");
	} else {
		// Failure
		$_SESSION["message"] = "Subject creation failed.";
		redirect_to("new_subject.php");
	}
	
} else {
	// This is probably a GET request
	redirect_to("new_subject.php");
}

?>

<div id="main">
	<div id="navigation">
		<?php echo navigation($current_subject, $current_page); ?>
	</div>
	<div id="page">
		<?php echo message(); ?>
		<?php $errors = errors(); ?>
		<?php echo form_errors($errors); ?>

		<h2>Edit Page</h2>
		<form action="create_page.php?subject=<?php echo $_GET["subject"]; ?>" method="post">
			<p>Page name:
				<input type="text" name="menu_name" value="" />
			</p>
			<p>Position:
				<select name="position">
					<?php
					$page_set = find_pages_for_subject($current_subject["id"]);
					$page_count = mysqli_num_rows($page_set);
					for($count=1; $count <= ($page_count + 1); $count++) {
						echo "<option value=\"{$count}\">{$count}</option>";
					}
					?>
				</select>
			</p>
			<p>Visible:
				<input type="radio" name="visible" value="0" /> No
				&nbsp;
				<input type="radio" name="visible" value="1" /> Yes
			</p><br />
			<textarea name="content" rows="4" cols="50"></textarea><br />
			<input type="submit" name="submit" value="Create Page" />
		</form>
		<br />
		<a href="manage_content.php">Cancel</a>
	</div>
</div>



<?php
	if (isset($connection)) { mysqli_close($connection); }
?>
