<?php
session_start();
require '../../config.php';

if ($_SESSION['log'] != true) {
  echo "<p>Please log in</p>";
  header("Location: ../login.php");
  exit();
}

$error = $message = $author_id = "";

if (isset($_POST['save_book'])) {

  $title = mysqli_real_escape_string($con, $_POST['title']);
  $publish_date = mysqli_real_escape_string($con, $_POST['publish_date']);
  $number_of_pages = mysqli_real_escape_string($con, $_POST['number_of_pages']);
  $genre = mysqli_real_escape_string($con, $_POST['genre']);
  if (isset($_POST['author_id'])) {
    $author_id = mysqli_real_escape_string($con, $_POST['author_id']);
  } else {
    $author_id = null; // or some default value
  }

  $sql = "SELECT * FROM books WHERE title = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param('s', $title);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $_SESSION['error'] = "Book already exists.";
  } else {

    $sql = "INSERT INTO books (title, publish_date, number_of_pages, genre, author_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssssi', $title, $publish_date, $number_of_pages, $genre, $author_id);
    $result = $stmt->execute();

    if ($result) {
      $_SESSION['message'] = "Book added successfully!";
    } else {
      $_SESSION['error'] = "Failed to add book.";
    }
  }

  header("Location: create-book.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>New Book</title>
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
              <i class="bi bi-book me-1"></i><?php echo $_SESSION['message']; ?>
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
          <h2 class="mt-2">New Book</h2>
          <span class="mt-3 ms-auto"><a href="../bookTable.php"><button class="btn btn-color-secondary">Back</button></a></span>
        </div>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="needs-validation" novalidate>
          <div class="form-floating mt-3">
            <input type="text" name="title" placeholder="" class="form-control" required />
            <label for="title" class="form-label">Title:</label>
            <div class="invalid-feedback">Please enter a title.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="date" name="publish_date" placeholder="" class="form-control" required />
            <label for="publish_date" class="form-label">Publish Date:</label>
            <div class="invalid-feedback">Please select a publish date.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="text" name="number_of_pages" placeholder="" class="form-control" required />
            <label for="number_of_pages" class="form-label">Number of Pages:</label>
            <div class="invalid-feedback">Please enter the number of pages.</div>
          </div>

          <div class="form-floating mt-3">
            <input type="text" name="genre" placeholder="" class="form-control" required />
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
                    <option value="<?= $content['author_id']; ?>"><?= $content['author_name']; ?></option>
              <?php
                  }
                }
              }
              ?>
            </select>
            <label for="floatingSelect">Author:</label>
          </div>

          <hr class="mx-5" />

          <div class="d-grid col-4 mx-auto my-3">
            <input class="btn btn-color" type="submit" name="save_book" value="Add Book" />
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