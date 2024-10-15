<?php
require_once("../config.php");

// Start or resume the session
session_start();

if ($_SESSION['log'] != true) {
  echo "<p>Please log in</p>";

  header("Location: ./login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" type="text/css" href="../style/style.css" />

  <style>
    body {
      background: url('https://images.unsplash.com/photo-1595123550441-d377e017de6a?q=80&w=2612&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
    }

    .row {
      background-color: transparent;
      margin-top: 3%;
    }

    .col {
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      transition: all 0.3s ease;
    }

    .col:hover {
      transform: scale(1.05);
    }

    .card {
      background-color: #fffdfb;
    }

    .card img {
      width: 100%;
      height: auto;
      object-fit: cover;
      aspect-ratio: 16/9;
    }

    .card-body {
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    @media only screen and (max-width: 768px) {
      .col {
        margin-left: auto;
        margin-right: auto;
        width: 380px;
      }

      main {
        margin-top: 16px;

      }
    }
  </style>
</head>

<body>
  <?php include("./partials/header.php"); ?>

  <main>
    <div class="container mb-3">
      <div class="row d-grid col-11 mx-auto">
        <!-- display alert -->
        <?php
        if (isset($_SESSION['msg'])) {
        ?>
          <div class="alert alert-succuss mb-0" role="alert">
            <div>
              <i class="bi bi-person-gear me-1"></i><?php echo $_SESSION['msg']; ?>
            </div>
          </div>
        <?php
          unset($_SESSION['msg']);
        } else if (isset($_SESSION['error'])) {
        ?>
          <div class="alert alert-danger mb-0" role="alert">
            <div>
              <i class="bi bi-exclamation-triangle-fill me-1"></i><?php echo $_SESSION['error']; ?>
            </div>
          </div>
        <?php
          unset($_SESSION['error']);
        }
        ?>
      </div>
      <div class="row row-cols-1 row-cols-md-2 g-3">
        <div class="col">
          <div class="card me-0 ms-md-5">
            <a href="authorTable.php"><img src="https://rwafriends.files.wordpress.com/2022/10/40033949132_f288bebcec_k.jpg" class="card-img-top" alt="Author image" /></a>
            <div class="card-body">
              <h5 class="card-title">Author Table</h5>
              <p class="card-text">
                This table showcases authors' details and their published works.
              </p>
              <a href="authorTable.php"><button class="btn btn-color">Open Table</button></a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card me-0 me-md-5">
            <a href="bookTable.php"><img src="https://plus.unsplash.com/premium_photo-1663127861345-cd4a2e05591f?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8c3RhY2slMjBvZiUyMGJvb2tzfGVufDB8fDB8fHww" class="card-img-top" alt="Books Image" /></a>
            <div class="card-body">
              <h5 class="card-title">Books Table</h5>
              <p class="card-text">
                This table showcases the books currently available in the library.
              </p>
              <a href="bookTable.php"><button class="btn btn-color">Open Table</button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>