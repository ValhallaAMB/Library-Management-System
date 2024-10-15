<?php
require_once("../config.php");

// Start or resume the session
session_start();

if ($_SESSION['log'] != true) {
  echo "<p>Please log in</p>";
  header("Location: ./login.php");
  exit();
}

// ------------------- pagination & search bar -------------------
$limit = isset($_GET['limit']) ? $_GET['limit'] : 10; // Change this value based on your preference
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search bar
if (isset($_POST["submit"]) && !empty($_POST["search"])) {
  $search = mysqli_real_escape_string($con, $_POST["search"]);
  $sql = "SELECT * FROM authors WHERE author_id LIKE '%$search%' OR author_name LIKE '%$search%' OR date_of_birth LIKE '%$search%' OR gender LIKE '%$search%' OR bio LIKE '%$search%'";
} else {
  $sql = "SELECT * FROM authors LIMIT $offset, $limit";
}

$result = mysqli_query($con, $sql);

if ($result) {
  $authors = $result->fetch_all(MYSQLI_ASSOC);
} else {
  echo "Error: " . mysqli_error($con);
}

// Count total number of records for pagination
$total_records_query = "SELECT COUNT(*) as count FROM authors";
$total_records_result = mysqli_query($con, $total_records_query);
$total_records = mysqli_fetch_assoc($total_records_result)['count'];
$pages = ceil($total_records / $limit);
$previous = ($page > 1) ? $page - 1 : 1;
$next = ($page < $pages) ? $page + 1 : $pages;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Author Table</title>

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
      <div class="row mx-2 rounded-top-2">
        <!-- display alert -->
        <?php
        if (isset($_SESSION['deleteAuthorNotification'])) {
        ?>
          <div class="alert alert-warning mb-0 rounded-bottom-0" role="alert">
            <div>
              <i class="bi bi-person-x me-1"></i><?php echo $_SESSION['deleteAuthorNotification']; ?>
            </div>
          </div>
        <?php
          unset($_SESSION['deleteAuthorNotification']);
        } else if (isset($_SESSION['updateAuthorNotification'])) {
        ?>
          <div class="alert alert-info mb-0 rounded-bottom-0" role="alert">
            <div>
              <i class="bi bi-person-gear me-1"></i><?php echo $_SESSION['updateAuthorNotification']; ?>
            </div>
          </div>
        <?php
          unset($_SESSION['updateAuthorNotification']);
        }
        ?>

        <h2 class="mb-3 mt-2" style="color:white">Author Table</h2>
        <!-- search bar -->
        <div class="col">
          <br>
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Search" name="search" />
              <button class="btn btn-color-secondary" type="submit" name="submit">
                Search
              </button>
            </div>
          </form>
        </div>

        <!-- items per page dropdown & add new author button -->
        <div class="col text-end">
          <a href="./authors/create-author.php" class="btn btn-color mb-1 mb-lg-0">Add new author<i class="bi bi-plus-circle ms-2"></i></a>
          <div class="btn-group">
            <button type="button" class="btn btn-color-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              Items per Page
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="?limit=10">10</a></li>
              <li><a class="dropdown-item" href="?limit=30">30</a></li>
              <li><a class="dropdown-item" href="?limit=50">50</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="row mx-2 px-3 table-responsive">
        <table class="table table-hover" id="example">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Name</th>
              <th scope="col">DOB</th>
              <th scope="col">Gender</th>
              <th scope="col">Bio</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody class="table-group-divider">
            <?php
            foreach ($authors as $content) {
            ?>
              <tr>
                <td><?= $content['author_id'] ?></td>
                <td><?= $content['author_name'] ?></td>
                <td><?= $content['date_of_birth'] ?></td>
                <td><?= $content['gender'] ?></td>
                <td><?= $content['bio'] ?></td>
                <td>
                  <a href="./authors/view-author.php?id=<?= $content['author_id'] ?>" class="btn btn-outline-secondary"><i class="bi bi-binoculars"></i></a>
                  <a href="./authors/update-author.php?id=<?= $content['author_id'] ?>" class="btn btn-outline-success"><i class="bi bi-pencil-square"></i></a>
                  <a class="btn btn-outline-danger deletebtn"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
        <!-- Pagination -->
        <nav class="d-flex justify-content-center">
          <ul class="pagination">
            <li class="page-item">
              <a class="page-link" href="authorTable.php?page=<?= $previous ?>"><i class="bi bi-chevron-double-left"></i></a>
            </li>
            <?php for ($i = 1; $i <= $pages; $i++) : ?>
              <li class="page-item"><a class="page-link" href="authorTable.php?page=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
            <li class="page-item">
              <a class="page-link" href="authorTable.php?page=<?= $next ?>"><i class="bi bi-chevron-double-right"></i></a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </main>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"> Delete Author Data </h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="./authors/delete-author.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="author_id" id="delete_id">
            <h6>Do you want to delete this author?</h6>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-color-secondary" data-bs-dismiss="modal"> NO </button>
            <button type="submit" name="deletedata" class="btn btn-color"> Yes </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <script>
    $(document).ready(function() {
      $('.deletebtn').on('click', function() {
        $('#deleteModal').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function() {
          return $(this).text();
        }).get();
        console.log(data);
        $('#delete_id').val(data[0]);
      });
    });
  </script>
</body>

</html>