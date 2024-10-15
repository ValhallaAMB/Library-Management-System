<?php
session_start();
require '../../config.php';

if ($_SESSION['log'] != true) {
    echo "<p>Please log in</p>";
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['deletedata']) && isset($_POST['author_id'])) {
    $author_id = mysqli_real_escape_string($con, $_POST['author_id']);

    // Start transaction
    mysqli_begin_transaction($con);

    // Delete author from authors table
    $sql = "DELETE FROM authors WHERE author_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $author_id);

    if (mysqli_stmt_execute($stmt)) {
        // Delete related books from books table
        $sql = "DELETE FROM books WHERE author_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $author_id);
        if (mysqli_stmt_execute($stmt)) {
            // Commit transaction
            mysqli_commit($con);
            $_SESSION['deleteAuthorNotification'] = "Author and related books deleted successfully!";
            header("Location: ../authorTable.php");
            exit();
        } else {
            // Rollback transaction
            mysqli_rollback($con);
            $_SESSION['status'] = "Failed to delete related books: " . mysqli_error($con);
            header("Location: ../authorTable.php");
            exit();
        }
    } else {
        // Rollback transaction
        mysqli_rollback($con);
        $_SESSION['status'] = "Failed to delete author: " . mysqli_error($con);
        header("Location: ../authorTable.php");
        exit();
    }
} else {
    $_SESSION['status'] = "Invalid request";
    header("Location: ../authorTable.php");
    exit();
}
