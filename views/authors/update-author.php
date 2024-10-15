<?php
session_start();
require '../../config.php';

if (!isset($_SESSION['log']) || $_SESSION['log'] != true) {
    header("Location: ../login.php");
    exit();
}

$authorRow = [];
if (isset($_GET['id'])) {
    $author_id = mysqli_real_escape_string($con, $_GET['id']);
    $sql = "SELECT * FROM authors WHERE author_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $author_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $authorRow = $result->fetch_assoc();
}

if (isset($_POST['update_author'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $author_name = mysqli_real_escape_string($con, $_POST['author_name']);
    $date_of_birth = mysqli_real_escape_string($con, $_POST['date_of_birth']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $bio = mysqli_real_escape_string($con, $_POST['bio']);

    $sql = "UPDATE authors SET author_name = ?, date_of_birth = ?, gender = ?, bio = ? WHERE author_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssssi', $author_name, $date_of_birth, $gender, $bio, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['updateAuthorNotification'] = "Author updated successfully!";
    } else {
        $_SESSION['error'] = "Author update unsuccessful";
        header("Location: update-author.php");
    }

    header("Location: ../authorTable.php");
    exit(0);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Author</title>
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
                <?php if (isset($_SESSION['error'])) { ?>
                    <div class="alert alert-success mb-0 rounded-bottom-0" role="alert">
                        <div>
                            <i class="bi bi-person-add me-1"></i><?php echo $_SESSION['error']; ?>
                        </div>
                    </div>
                <?php
                    unset($_SESSION['error']);
                }
                ?>
                <div class="d-inline-flex">
                    <h2 class="mt-3">Update Author</h2>
                    <span class="mt-3 ms-auto"><a href="../authorTable.php"><button class="btn btn-color-secondary">Back</button></a></span>
                </div>

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . $_GET['id']); ?>" method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']); ?>">

                    <div class="form-floating mt-3">
                        <input type="text" name="author_name" value="<?= htmlspecialchars($authorRow['author_name']); ?>" placeholder="Author Name" class="form-control" required />
                        <label for="name" class="form-label">Name:</label>
                        <div class="invalid-feedback">Please enter a name.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="date" name="date_of_birth" value="<?= htmlspecialchars($authorRow['date_of_birth']); ?>" placeholder="Birthday" class="form-control" required />
                        <label for="DOB" class="form-label">DOB:</label>
                        <div class="invalid-feedback">Please select a date of birth.</div>
                    </div>

                    <div class="mt-3 ms-2 text-center">
                        <label for="gender" id="gender" class="form-label me-2">Gender:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Male" <?= ($authorRow['gender'] == 'Male') ? 'checked' : ''; ?> id="flexRadioMale" required />
                            <label class="form-check-label" for="flexRadioMale">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Female" <?= ($authorRow['gender'] == 'Female') ? 'checked' : ''; ?> id="flexRadioFemale" required />
                            <label class="form-check-label" for="flexRadioFemale">Female</label>
                        </div>
                        <div class="invalid-feedback">Please select a gender.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="text" class="form-control" name="bio" id="floatingInputGroup1" value="<?= htmlspecialchars($authorRow['bio']); ?>" placeholder="Bio" required>
                        <label for="floatingInputGroup1">Biography</label>
                        <div class="invalid-feedback">Please enter a biography.</div>
                    </div>

                    <div class="d-grid col-4 mx-auto my-3">
                        <button class="btn btn-outline-success btn-lg" type="submit" name="update_author">Update Author</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        // JavaScript Validation Logic
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
