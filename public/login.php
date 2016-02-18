<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>

<?php
$username = "";
if (isset($_POST['submit'])) {
// Process the form


// validations
    $required_fields = array("username", "password");
    validate_presences($required_fields);

    $fields_with_max_lengths = array("username" => 50, "password" => 60);
    validate_max_lengths($fields_with_max_lengths);

    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        redirect_to("login.php");
    }
    // set variables for query
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Attempt login
    $found_admin = attempt_login($username, $password);

    if ($found_admin) {
        // Success
        // Mark user as logged in
        $_SESSION["admin_id"] = $found_admin["id"];
        $_SESSION["username"] = $found_admin["username"];
        redirect_to("admin.php");
    } else {
        // Failure
        $_SESSION["message"] = "Username and password incorrect.";
    }
}
?>
<?php find_selected_page(); ?>

<div id="main">
    <div id="navigation">
    </div>
    <div id="page">
        <?php echo message(); ?>
        <?php $errors = errors(); ?>
        <?php echo form_errors($errors); ?>
        <h2>Login</h2>
        <form action="login.php" method="post">
            <p>Username:
                <input type="text" name="username" value="<?php echo htmlentities($username); ?>"/>
            </p>
            <p>Password:
                <input type="password" name="password" value=""/>
            </p>
            <input type="submit" name="submit" value="Submit"/>
        </form>
        <br/>
    </div>
</div>

<?php include("../includes/layouts/footer.php"); ?>
