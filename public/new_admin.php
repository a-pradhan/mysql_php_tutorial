<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>

<?php
if (isset($_POST['submit'])) {
// Process the form

    $username = mysql_prep($_POST["username"]);
    $password = password_encrypt($_POST["password"]);

// validations
    $required_fields = array("username", "password");
    validate_presences($required_fields);

    $fields_with_max_lengths = array("username" => 50, "password" => 60);
    validate_max_lengths($fields_with_max_lengths);

    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        redirect_to("new_admin.php");
    }

    $query = "INSERT INTO admins (";
    $query .= "username, hashed_password";
    $query .= ") VALUES (";
    $query .= "'{$username}', '{$password}'";
    $query .= ")";
    $result = mysqli_query($connection, $query);

    if ($result) {
        // Success
        $_SESSION["message"] = "Admin Created Successfully.";
        redirect_to("manage_admins.php");
    } else {
        // Failure
        $message = "Database insert failed- - subject_id: {$_GET["subject"]}, menu_name: {$menu_name}, position: {$position},
        visible: {$visible}, content: {$content}. SQL query: {$query}";
        redirect_to("new_admin.php");
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
        <h2>Create Admin User</h2>
        <form action="new_admin.php?; ?>" method="post">
            <p>Username:
                <input type="text" name="username" value=""/>
            </p>
            <p>Password:
                <input type="password" name="password" value=""/>
            </p>
            <input type="submit" name="submit" value="Create Admin"/>
        </form>
        <br/>
        <a href="manage_content.php">Cancel</a>
    </div>
</div>

<?php include("../includes/layouts/footer.php"); ?>
