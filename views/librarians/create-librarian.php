<?php
session_start();
require '../../config.php';

// Check if user is logged in
if (!isset($_SESSION['log']) || $_SESSION['log'] != true) {
  $_SESSION['error'] = "Please log in";
  header("Location: ../login.php");
  exit();
}

if (isset($_POST['save_librarian'])) {

  // Escape input data to prevent SQL injection
  $firstName = mysqli_real_escape_string($con, $_POST['first_name']);
  $lastName = mysqli_real_escape_string($con, $_POST['last_name']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $password = mysqli_real_escape_string($con, $_POST['password']);
  $repeatPassword = mysqli_real_escape_string($con, $_POST['confirm_password']);

  // Check if the librarian already exists
  $sql = "SELECT * FROM librarians WHERE first_name = ? OR last_name = ? OR email = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param('sss', $firstName, $lastName, $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $_SESSION['error'] = "Credentials already exist!";
  } elseif ($password != $repeatPassword) {
    $_SESSION['error'] = "Password and confirm password do not match!";
  } else {
    // Hash the passwords
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new librarian into the database
    $sql = "INSERT INTO librarians (first_name, last_name, email, pass) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssss', $firstName, $lastName, $email, $hashedPassword);
    $result = $stmt->execute();

    if ($result) {
      $_SESSION['message'] = "Librarian added successfully!";
    } else {
      $_SESSION['error'] = "Librarian add unsuccessful!";
    }
  }
  // Redirect to create-librarian.php
  header("Location: create-librarian.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>New Librarian</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../../style/style.css" />

  <style>
    .container {
      max-width: 800px;
      min-height: 100vh;
    }

    body {
      background: url('https://images.unsplash.com/photo-1595123550441-d377e017de6a?q=80&w=2612&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
    }
  </style>
</head>

<body>
  <main>
    <div class="container d-flex px-5 align-items-center justify-content-center mt-3 mb-3">
      <div class="row rounded-2">
        <?php
        if (isset($_SESSION['message'])) {
        ?>
          <div class="alert alert-success mb-0 rounded-bottom-0" role="alert">
            <div>
              <i class="bi bi-person-plus me-1"></i><?php echo $_SESSION['message']; ?>
            </div>
          </div>
        <?php
          unset($_SESSION['message']);
        } else if (isset($_SESSION['error'])) {
        ?>
          <div class="alert alert-danger mb-0 rounded-bottom-0" role="alert">
            <div>
              <i class="bi bi-exclamation-triangle me-1"></i><?php echo $_SESSION['error']; ?>
            </div>
          </div>
        <?php unset($_SESSION['error']);
        } ?>
        <div class="d-inline-flex">
          <h2 class="mt-2">New Librarian</h2>
          <span class="mt-3 ms-auto"><a href="../librarianTable.php"><button class="btn btn-color-secondary">Back</button></a></span>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="needs-validation" novalidate id="librarianForm">
          <div class="form-floating mt-3">
            <input type="text" name="first_name" placeholder="First name" class="form-control" required />
            <label for="first_name" class="form-label">First name:</label>
            <div class="invalid-feedback">Please enter a first name.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="text" name="last_name" placeholder="Last name" class="form-control" required />
            <label for="last_name" class="form-label">Last name:</label>
            <div class="invalid-feedback">Please enter a last name.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="email" name="email" placeholder="Email" class="form-control" required />
            <label for="email" class="form-label">Email:</label>
            <div class="invalid-feedback">Please enter an email.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="password" name="password" placeholder="Password" class="form-control" required />
            <label for="password" class="form-label">Password:</label>
            <div class="invalid-feedback">Please enter a password.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" required />
            <label for="confirm_password" class="form-label">Confirm Password:</label>
            <div class="invalid-feedback">Please confirm your password.</div>
          </div>

          <hr class="mx-5" />

          <div class="d-grid col-4 mx-auto my-3">
            <input class="btn btn-color" type="submit" name="save_librarian" value="Add Librarian" />
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