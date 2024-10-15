<?php
session_start();

require '../../config.php';

if ($_SESSION['log'] != true) {
    echo "<p>Please log in</p>";

    header("Location: ../login.php");
    exit();
}

$librarian_id = mysqli_real_escape_string($con, $_POST['librarian_id']);

// Start transaction
mysqli_begin_transaction($con);

// Delete user from user table
$sql = "DELETE FROM librarians WHERE librarian_id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $librarian_id);
if (mysqli_stmt_execute($stmt)) {
    // Commit transaction
    mysqli_commit($con);
    $_SESSION['message'] = "User deleted successfully";

    if ($_SESSION['id'] == $librarian_id) {
        header("Location: ../login.php");
    } else {
        header("Location: ../librarianTable.php");
    }
    exit();
} else {
    // Rollback transaction
    mysqli_rollback($con);
    $_SESSION['message'] = "Failed to delete user";
}
