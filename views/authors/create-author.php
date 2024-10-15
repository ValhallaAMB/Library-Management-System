<?php
session_start();
require '../../config.php';

if ($_SESSION['log'] != true) {
  echo "<p>Please log in</p>";
  header("Location: ../login.php");
  exit();
}

$error = "";
$message = "";

if (isset($_POST['save_author'])) {
  $author_name = mysqli_real_escape_string($con, $_POST['author_name']);
  $dob = mysqli_real_escape_string($con, $_POST['date_of_birth']);
  $gender = mysqli_real_escape_string($con, $_POST['gender']);
  $bio = mysqli_real_escape_string($con, $_POST['bio']);

  $sql = "SELECT * FROM authors WHERE author_name = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param('s', $author_name);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $_SESSION['error'] = "Author already exists!";
  } else {
    $sql = "INSERT INTO authors (author_name, date_of_birth, gender, bio) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssss', $author_name, $dob, $gender, $bio);
    $result = $stmt->execute();

    if ($result) {
      $_SESSION['createAuthorNotification'] = "Author added successfully!";
    } else {
      $_SESSION['error'] = "Failed to add author.";
    }
  }

  header("Location: create-author.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>New Author</title>
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
        <!-- display alert -->
        <?php
        if (isset($_SESSION['createAuthorNotification'])) {
        ?>
          <div class="alert alert-success mb-0 rounded-bottom-0" role="alert">
            <div>
              <i class="bi bi-person-add me-1"></i><?php echo $_SESSION['createAuthorNotification']; ?>
            </div>
          </div>
        <?php
          unset($_SESSION['createAuthorNotification']);
        } else if (isset($_SESSION['error'])) {
        ?>
          <div class="alert alert-danger mb-0 rounded-bottom-0" role="alert">
            <div>
              <i class="bi bi-person-add me-1"></i><?php echo $_SESSION['error']; ?>
            </div>
          </div>
        <?php unset($_SESSION['error']);
        } ?>
        <div class="d-inline-flex">
          <h2 class="mt-2">New Author</h2>
          <span class="mt-3 ms-auto"><a href="../authorTable.php"><button class="btn btn-color-secondary">Back</button></a></span>
        </div>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="needs-validation" novalidate>
          <div class="form-floating mt-3">
            <input type="text" name="author_name" placeholder="" class="form-control" required />
            <label for="name" class="form-label">Name:</label>
            <div class="invalid-feedback">Please enter a name.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="date" name="date_of_birth" placeholder="" class="form-control" required />
            <label for="name" class="form-label">Date of Birth:</label>
            <div class="invalid-feedback">Please select a birth date.</div>
          </div>

          <div class="mt-3 ms-2 text-center">
            <label for="gender" id="gender" class="form-label me-2">Gender:</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" value="Male" id="flexRadioMale" required />
              <label class="form-check-label" for="flexRadioMale">Male</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" value="Female" id="flexRadioFemale" required />
              <label class="form-check-label" for="flexRadioFemale">Female</label>
            </div>
          </div>

          <div class="input-group mb-4">
            <div class="form-floating">
              <input type="bio" class="form-control" name="bio" id="floatingInputGroup1" placeholder="bio" style="font-size: 20px" required>
              <label for="floatingInputGroup1">Bio</label>
            </div>
          </div>

          <hr class="mx-5" />

          <div class="d-grid col-4 mx-auto my-3">
            <input class="btn btn-color" type="submit" name="save_author" value="Add Author" />
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