<?php
session_start();
require '../../config.php';

if ($_SESSION['log'] != true) {
    echo "<p>Please log in</p>";

    header("Location: ../login.php");
    exit();
}

$book_id = mysqli_real_escape_string($con, $_POST['book_id']);

// Start transaction
mysqli_begin_transaction($con);

// Delete user from user table
$sql = "DELETE FROM books WHERE book_id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $book_id);

if (mysqli_stmt_execute($stmt)) {
    // Commit transaction
    mysqli_commit($con);
    $_SESSION['message'] = "Book deleted successfully";
    header("Location: ../bookTable.php");
    exit();
} else {
    // Rollback transaction
    mysqli_rollback($con);
    $_SESSION['message'] = "Failed to delete book";
}
