<?php
session_start();
require '../config.php';

if ($_SESSION['log'] != true) {
    echo "<p>Please log in</p>";
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['update_profile'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $firstName = mysqli_real_escape_string($con, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($con, $_POST['last_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $repeatPassword = mysqli_real_escape_string($con, $_POST['confirm_password']);

    if ($password != $repeatPassword) {
        $_SESSION['error'] = "Password and confirm password do not match!";
    } else {
        if (empty($password) || empty($repeatPassword)) {
            $sql = "SELECT pass FROM librarians WHERE librarian_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $hashedPassword = $row['pass'];
            $confirmHashPassword = $row['pass'];
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $confirmHashPassword = password_hash($repeatPassword, PASSWORD_DEFAULT);
        }
        $sql = "UPDATE librarians SET 
            first_name = ?,
            last_name = ?,
            email = ?,
            pass = ?,
            confirm_pass = ?
            WHERE librarian_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('sssssi', $firstName, $lastName, $email, $hashedPassword, $confirmHashPassword, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['msg'] = "Librarian has been successfully updated!";
            header("Location: ./dashboard.php");
            exit(0);
        } else {
            $_SESSION['error'] = "Librarian update unsuccessful!";
            header("Location: ./dashboard.php");
            exit(0);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Librarian</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="../style/style.css" />
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
                <?php if (isset($_SESSION['error'])) {
                ?>
                    <div class="alert alert-danger mb-0 rounded-bottom-0" role="alert">
                        <div>
                            <i class="bi bi-exclamation-triangle me-1"></i><?php echo $_SESSION['error']; ?>
                        </div>
                    </div>
                <?php unset($_SESSION['error']);
                } ?>

                <div class="d-inline-flex">
                    <h2 class="mt-3">Update Librarian</h2>
                    <span class="mt-3 ms-auto"><a href="dashboard.php"><button class="btn btn-color-secondary">Back</button></a></span>
                </div>

                <?php
                if (isset($_GET['id'])) {
                    $librarianId = mysqli_real_escape_string($con, $_GET['id']);
                    $sql = "SELECT librarian_id, first_name, last_name, email FROM librarians WHERE librarian_id = ?";
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param("i", $librarianId);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Fetch the user row
                    $userRow = $result->fetch_assoc();
                    $isAdmin = ($userRow['first_name'] === 'admin'); // Check if the user is admin
                }
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . $_GET['id']); ?>" method="POST" class="needs-validation" novalidate id="librarianForm">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']); ?>">

                    <div class="form-floating mt-3">
                        <input type="text" name="first_name" placeholder="First name" class="form-control" value="<?= $userRow['first_name']; ?>" <?= $isAdmin ? 'disabled' : ''; ?> required />
                        <label for="first_name" class="form-label">First name:</label>
                        <div class="invalid-feedback">Please enter a first name.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="text" name="last_name" placeholder="Last name" class="form-control" value="<?= $userRow['last_name']; ?>" <?= $isAdmin ? 'disabled' : ''; ?> required />
                        <label for="last_name" class="form-label">Last name:</label>
                        <div class="invalid-feedback">Please enter a last name.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="email" name="email" placeholder="Email" class="form-control" value="<?= $userRow['email']; ?>" <?= $isAdmin ? 'disabled' : ''; ?> required />
                        <label for="email" class="form-label">Email:</label>
                        <div class="invalid-feedback">Please enter an email.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="password" name="password" placeholder="Password" class="form-control" <?= $isAdmin ? 'disabled' : ''; ?> />
                        <label for="password" class="form-label">Password:</label>
                        <div class="invalid-feedback">Please enter a password.</div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" <?= $isAdmin ? 'disabled' : ''; ?> />
                        <label for="confirm_password" class="form-label">Confirm Password:</label>
                        <div class="invalid-feedback">Please confirm your password.</div>
                    </div>

                    <div class="d-grid col-4 mx-auto my-3">
                        <button class="btn btn-outline-success btn-lg" type="submit" name="update_profile" <?= $isAdmin ? 'disabled' : ''; ?>>Update Profile</button>
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

                    var password = form.querySelector('[name="password"]').value;
                    var confirmPassword = form.querySelector('[name="confirm_password"]').value;

                    if (password !== confirmPassword) {
                        event.preventDefault();
                        event.stopPropagation();
                        form.querySelector('[name="confirm_password"]').setCustomValidity("Passwords do not match");
                        form.classList.add('was-validated');
                    } else {
                        form.querySelector('[name="confirm_password"]').setCustomValidity("");
                    }

                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>

</html>