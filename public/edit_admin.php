<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>

<?php
if (isset($_POST['submit'])) {
// Process the form
    $id = (int) mysqli_real_escape_string($connection, $_GET["admin_id"]);
    $username = mysql_prep($_POST["username"]);
    $password = password_encrypt($_POST["password"]);

// validations
    $required_fields = array("username", "password");
    validate_presences($required_fields);

    $fields_with_max_lengths = array("username" => 50, "password" => 60);
    validate_max_lengths($fields_with_max_lengths);

    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        redirect_to("edit_admin.php?admin_id={$id}");
    }

    $query = "UPDATE admins SET ";
    $query .= "username = '{$username}', ";
    $query .= "hashed_password = '{$password}' ";
    $query .= "WHERE id = {$id} ";
    $query .= "LIMIT 1";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_affected_rows($connection) == 1) {
        // Success
        $_SESSION["message"] = "Admin Changes Saved Successfully.";
        redirect_to("manage_admins.php");
    } else {
        // Failure
        $message = "Admin Changes Failed";
        redirect_to("edit_admin.php?admin_id={$id}");
    }
}
?>
<?php find_selected_page(); ?>

<div id="main">
    <div id="navigation">
        <?php echo navigation($current_subject, $current_page); ?>
    </div>
    <div id="page">
        <?php echo message(); ?>
        <?php $errors = errors(); ?>
        <?php echo form_errors($errors); ?>
        <?php $admin = find_admin_by_id($_GET["admin_id"]); ?>
        <h2>Edit Admin User</h2>
        <form action="edit_admin.php?admin_id=<?php echo htmlentities($admin["id"]); ?>" method="post">
            <p>Username:
                <input type="text" name="username" value="<?php echo htmlentities($admin["username"]); ?>"/>
            </p>
            <p>Password:
                <input type="password" name="password" value=""/>
            </p>
            <input type="submit" name="submit" value="Save Changes"/>
        </form>
        <br/>
        <a href="manage_admins.php">Cancel</a>
    </div>
</div>

<?php include("../includes/layouts/footer.php"); ?>
