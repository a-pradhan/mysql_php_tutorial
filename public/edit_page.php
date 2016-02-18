<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php find_selected_page(); ?>
<?php if (!$current_page) {
    redirect_to("manage_content.php");
} ?>
<?php require_once("../includes/validation_functions.php"); ?>

<?php
if (isset($_POST['submit'])) {
// Process the form
// validations
    $required_fields = array("menu_name", "position", "visible", "content");
    validate_presences($required_fields);

    $fields_with_max_lengths = array("menu_name" => 30, "content" => 255);
    validate_max_lengths($fields_with_max_lengths);


    if (empty($errors)) {
        $id = $current_page["id"];
        $page_subject_id = $current_page["subject_id"];
        $menu_name = mysql_prep($_POST["menu_name"]);
        $position = (int)$_POST["position"];
        $visible = (int)$_POST["visible"];
        $content = mysql_prep($_POST["content"]);

        $query = "UPDATE pages SET ";
        $query .= "subject_id = {$page_subject_id}, ";
        $query .= "menu_name = '{$menu_name}', ";
        $query .= "position = {$position}, ";
        $query .= "visible = {$visible} , ";
        $query .= "content = '{$content}' ";
        $query .= "WHERE id = {$id} ";
        $query .= "LIMIT 1";
        $result = mysqli_query($connection, $query);

        if ($result && mysqli_affected_rows($connection) == 0) {
            // Success
            $_SESSION["message"] = "Page created.";
            redirect_to("manage_content.php");
        } else {
            // Failure
            $message = "Database insert failed- - subject_id: {$current_subject["subject_id"]}, menu_name: {$menu_name}, position: {$position},
        visible: {$visible}, content: {$content}. SQL query: {$query}";

        }
    }

} else {
    // This is probably a GET request
    //redirect_to("manage_content.php");
}

?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

<div id="main">
    <div id="navigation">
        <?php echo navigation($current_subject, $current_page); ?>
    </div>
    <div id="page">

        <?php if (!empty($message)) {
            echo "<div class\"message\"" . htmlentities($message) . "</div>";
        } ?>
        <?php echo form_errors($errors); ?>

        <h2>Edit Page</h2>
        <form action="edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>" method="post">
            <p>Page name:
                <input type="text" name="menu_name" value="<?php echo htmlentities($current_page["menu_name"]); ?>"/>
            </p>
            <p>Position:
                <select name="position">
                    <?php
                    $page_set = find_pages_for_subject($current_page["subject_id"], false);
                    $page_count = mysqli_num_rows($page_set);
                    for ($count = 1; $count <= ($page_count); $count++) {

                        echo "<option value=\"{$count}\">";
                        if ($current_page["position"] == $count) {
                            echo "{$count}";
                        }
                        echo "</option>";
                    }
                    ?>
                </select>
            </p>
            <p>Visible:
                <input type="radio" name="visible" value="0" <?php if ($current_page["visible"] == 0) {
                    echo "checked";
                } ?> /> No
                &nbsp;
                <input type="radio" name="visible" value="1" <?php if ($current_page["visible"] == 1) {
                    echo "checked";
                } ?> /> Yes
            </p><br/>
            <textarea name="content" rows="4" cols="50"><?php echo $current_page["content"]; ?></textarea><br/>
            <input type="submit" name="submit" value="Edit Page"/>
        </form>
        <br/>
        <a href="manage_content.php">Cancel</a>&nbsp;
        <a href="delete_page.php?page=<?php echo urlencode($current_page["id"]); ?>"
           onclick="return confirm('Are you sure?');">Delete Page</a>
    </div>
</div>


<?php
if (isset($connection)) {
    mysqli_close($connection);
}
?>
