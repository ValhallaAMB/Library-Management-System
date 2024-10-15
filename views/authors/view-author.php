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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Author</title>
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
                        <h2 class="m-1">View Author
                            <a href="../authorTable.php" class="btn btn-color-secondary btn-lg ms-3 mb-2">Back</a>
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
                                <input type="text" class="form-control" name="author_name" id="floatingInputGroup1" value="<?= htmlspecialchars($authorRow['author_name']); ?>" placeholder="Author name" style="font-size: 20px" required disabled>
                                <label for="floatingInputGroup1">Author Name</label>
                            </div>
                        </div>

                        <div class="form-floating mt-3">
                            <input type="date" name="date_of_birth" placeholder="Date of birth" value="<?= htmlspecialchars($authorRow['date_of_birth']); ?>" class="form-control" required disabled />
                            <label for="name" class="form-label">Date of Birth</label>
                        </div>

                        <div class="mt-3 ms-2 text-center">
                            <label for="gender" id="gender" class="form-label me-2">Gender:</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" value="Male" <?= ($authorRow['gender'] == 'Male') ? 'checked' : ''; ?> id="flexRadioMale" required disabled />
                                <label class="form-check-label" for="flexRadioMale">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" value="Female" <?= ($authorRow['gender'] == 'Female') ? 'checked' : ''; ?> id="flexRadioFemale" required disabled />
                                <label class="form-check-label" for="flexRadioFemale">Female</label>
                            </div>
                        </div>

                        <div class="form-floating mt-3">
                            <textarea class="form-control" name="bio" id="floatingInputGroup1" style="font-size: 20px; height: 150px;" required disabled><?= htmlspecialchars($authorRow['bio']); ?></textarea>
                            <label for="floatingInputGroup1">Biography</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>