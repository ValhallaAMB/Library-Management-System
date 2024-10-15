<?php
session_start();
require '../../config.php';

if (!isset($_SESSION['log']) || $_SESSION['log'] != true) {
    header("Location: ../login.php");
    exit();
}

$librarianRow = [];
if (isset($_GET['id'])) {
    $librarianId = mysqli_real_escape_string($con, $_GET['id']);
    $sql = "SELECT librarian_id, first_name, last_name, email FROM librarians WHERE librarian_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $librarianId);
    $stmt->execute();
    $result = $stmt->get_result();
    $librarianRow = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Librarian</title>
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
                <div class="card">
                    <div class="card-header mt-3">
                        <h2 class="m-2">View Librarian
                            <a href="../librarianTable.php" class="btn btn-color-secondary btn-lg ms-3 mb-2">Back</a>
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="id" id="floatingInputGroup1" value="<?= htmlspecialchars($_GET['id']); ?>" placeholder="id" style="font-size: 20px" required disabled>
                                <label for="floatingInputGroup1">ID</label>
                            </div>
                        </div>

                        <div class="col input-group mb-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="first_name" id="floatingInputGroup1" value="<?= htmlspecialchars($librarianRow['first_name']); ?>" placeholder="First Name" style="font-size: 20px" required disabled>
                                <label for="floatingInputGroup1">First Name</label>
                            </div>
                        </div>

                        <div class="col input-group mb-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="last_name" id="floatingInputGroup1" value="<?= htmlspecialchars($librarianRow['last_name']); ?>" placeholder="Last Name" style="font-size: 20px" required disabled>
                                <label for="floatingInputGroup1">Last Name</label>
                            </div>
                        </div>

                        <div class="col input-group mb-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="email" id="floatingInputGroup1" placeholder="Email" value="<?= htmlspecialchars($librarianRow['email']); ?>" style="font-size: 20px" required disabled>
                                <label for="floatingInputGroup1">Email</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>