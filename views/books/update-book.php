<?php
session_start();
require '../../config.php';

if ($_SESSION['log'] != true) {
    echo "<p>Please log in</p>";
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['update_book'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $publish_date = mysqli_real_escape_string($con, $_POST['publish_date']);
    $number_of_pages = mysqli_real_escape_string($con, $_POST['number_of_pages']);
    $genre = mysqli_real_escape_string($con, $_POST['genre']);
    $author_id = null;

    if (isset($_POST['author_id']) && !empty($_POST['author_id'])) {
        $author_id = mysqli_real_escape_string($con, $_POST['author_id']);
    }

    $sql = "UPDATE books SET title = ?, publish_date = ?, number_of_pages = ?, genre = ?, author_id = ? WHERE book_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssssii', $title, $publish_date, $number_of_pages, $genre, $author_id, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Book has been successfully updated";
    } else {
        $_SESSION['error'] = "Book update unsuccessful";
    }

    header("Location: ../bookTable.php");
    exit(0);
}

$bookRow = [];
if (isset($_GET['id'])) {
    $book_id = mysqli_real_escape_string($con, $_GET['id']);
    $sql = "SELECT * FROM books WHERE book_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookRow = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="../../style/style.css" />
    <style>
        .container {
            max-width: 800px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body {
            background: url('https://images.unsplash.com/photo-1595123550441-d377e017de6a?q=80&w=2612&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
        }
    </style>
</head>

<body>
    <main>
        <div class="container mt-3 mb-3">
            <div class="row rounded-2 p-4 bg-white shadow">
                <div class="d-inline-flex">
                    <h2 class="mt-3">Update Book</h2>
                    <span class="mt-3 ms-auto"><a href="../bookTable.php"><button class="btn btn-color-secondary">Back</button></a></span>
                </div>

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . $_GET['id']); ?>" method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']); ?>">

                    <div class="form-floating mt-3">
                        <input type="text" name="title" value="<?= htmlspecialchars($bookRow['title']); ?>" placeholder="Title" class="form-control" required />
                        <label for="title" class="form-label">Title:</label>
                        <div class="invalid-feedback">Please enter a title.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="date" name="publish_date" value="<?= htmlspecialchars($bookRow['publish_date']); ?>" placeholder="Publish Date" class="form-control" required />
                        <label for="publish_date" class="form-label">Publish Date:</label>
                        <div class="invalid-feedback">Please select a publish date.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="number" name="number_of_pages" value="<?= htmlspecialchars($bookRow['number_of_pages']); ?>" placeholder="Number of Pages" class="form-control" required />
                        <label for="number_of_pages" class="form-label">Number of Pages:</label>
                        <div class="invalid-feedback">Please enter the number of pages.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="text" name="genre" value="<?= htmlspecialchars($bookRow['genre']); ?>" placeholder="Genre" class="form-control" required />
                        <label for="genre" class="form-label">Genre:</label>
                        <div class="invalid-feedback">Please enter a genre.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <select class="form-select" name="author_id" id="floatingSelect" required>
                            <option selected disabled value="">Choose...</option>
                            <?php
                            $sql = "SELECT * FROM authors";
                            if ($result = mysqli_query($con, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    while ($content = mysqli_fetch_array($result)) {
                            ?>
                                        <option value="<?= $content['author_id'] ?>" <?= ($content['author_id'] == $bookRow['author_id']) ? 'selected' : ''; ?>><?= $content['author_name']; ?></option>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </select>
                        <label for="floatingSelect">Author:</label>
                        <div class="invalid-feedback">Please select an author.</div>
                    </div>

                    <div class="d-grid col-4 mx-auto my-3">
                        <button class="btn btn-outline-success btn-lg" type="submit" name="update_book">Update Book</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        (function() {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>

</html>