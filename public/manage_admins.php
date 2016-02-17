<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $layout_context = "admin"; ?>
<?php find_selected_page(); ?>
<?php include("../includes/layouts/header.php"); ?>

<div id="main">
    <div id="navigation">
        <br/>
        <a href="admin.php">&laquo; Main Menu</a>
        <?php echo navigation($current_subject, $current_page); ?>
    </div>
    <div id="page">
        <?php echo message(); ?>
        <?php echo list_admins(); ?>
        <a href="new_admin.php">+ Add New Admin User</a>
    </div>
</div>


<?php include("../includes/layouts/footer.php"); ?>


