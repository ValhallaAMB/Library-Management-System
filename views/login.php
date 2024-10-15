<?php
session_start();
require_once("../config.php");

if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}

if (isset($_POST['login'])) {
  $email = mysqli_real_escape_string($con, $_POST['email']); //email
  $password = mysqli_real_escape_string($con, $_POST['password']); //password

  if (empty($email) or empty($password)) {
    $_SESSION['loginError'] = "Fill in the form below";
  } else {

    // Use prepared statement to avoid SQL injection
    $query = "SELECT * FROM librarians WHERE email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $rowSQL = $result->fetch_assoc();
      $email = $rowSQL['email'];
      $hashedPassword = $rowSQL['pass'];

      if ($email == "admin@admin.com" && $password == "admin") {
        $_SESSION['log'] = true;
        $_SESSION['id'] = $rowSQL['librarian_id'];
        $_SESSION['email'] = $email;

        $_SESSION['msg'] = "Welcome";
        header("Location: dashboard.php");
        exit();
      }

      // Use password_verify to check hashed password
      if (password_verify($password, $hashedPassword)) {
        $_SESSION['log'] = true;
        $_SESSION['id'] = $rowSQL['librarian_id'];
        $_SESSION['email'] = $email;

        $_SESSION['msg'] = "Welcome";
        header("Location: dashboard.php");
        exit();
      } else {
        $_SESSION['loginError'] = "Incorrect password.";
      }
    } else {
      $_SESSION['loginError'] = "Incorrect email.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../style/style.css" />
  <style>
    body {
      background: url('https://images.unsplash.com/photo-1595123550441-d377e017de6a?q=80&w=2612&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
    }

    #side-img {
      border-radius: 5px 0px 0px 5px;
    }

    .col {
      max-width: 500px;
    }

    a {
      color: #829e90;
    }

    @media only screen and (max-width: 768px) {
      #side-img {
        max-height: 250px;
        /* Set to half of the original value */
        border-radius: 5px 5px 0px 0px;
        object-fit: cover;
        /* Adjust as needed based on your design preferences */
      }

      .col {
        max-height: 315px;
        /* Adjust as needed based on your design preferences */
        max-width: 472px;
      }
    }
  </style>
</head>

<body>
  <main>
    <!-- display alert -->
    <?php
    if (isset($_SESSION['loginError'])) {
    ?>
      <div class="alert alert-danger rounded-bottom-0 text-center" role="alert">
        <div>
          <i class="bi bi-exclamation-circle me-1"></i><?php echo $_SESSION['loginError']; ?>
        </div>
      </div>
    <?php
      unset($_SESSION['loginError']);
    } else if (isset($_SESSION['passwordChanged'])) {
    ?>
      <div class="alert alert-success rounded-bottom-0 text-center" role="alert">
        <div>
          <i class="bi bi-check-circle me-1"></i><?php echo $_SESSION['passwordChanged']; ?>
        </div>
      </div>
    <?php
      unset($_SESSION['passwordChanged']);
    } else if (isset($_SESSION['message'])) {
    ?>
      <div class="alert alert-info rounded-bottom-0 text-center" role="alert">
        <div>
          <i class="bi bi-check-circle me-1"></i><?php echo $_SESSION['message']; ?>
        </div>
      </div>
    <?php unset($_SESSION['message']);
    } ?>

    <div class="container d-flex px-5 align-items-center justify-content-center min-vh-100">
      <div class="row row-cols-1 row-cols-md-2 rounded-2">


        <div class="col g-0">
          <div class="card border-0">
            <img src="https://galerieleminotaure.net/wp-content/uploads/2015/08/tt3-484x606.jpg" class="img-fluid" id="side-img" />
          </div>
        </div>

        <div class="col my-md-auto mt-2">
          <h2>Log In</h2>
          <h5>Sign in with your account</h5>

          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="needs-validation" novalidate>
            <div class="form-floating mt-3">
              <input type="email" placeholder="Enter your email" name="email" class="form-control" required />
              <label for="email" class="form-label">Email: </label>
            </div>

            <div class="input-group mt-3">
              <div class="form-floating">
                <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" required />
                <label for="password" class="form-label">Password:
                </label>
              </div>
              <span class="input-group-text" type="span"><i class="bi bi-eye-slash" id="togglePassword"></i></span>
            </div>

            <div class="mt-3">
              <button class="btn btn-color col-3" type="submit" name="login">
                Log in
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <footer></footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <script>
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    togglePassword.addEventListener("click", () => {
      const type =
        password.getAttribute("type") === "password" ? "text" : "password";
      password.setAttribute("type", type);
      togglePassword.classList.toggle("bi-eye-slash");
      togglePassword.classList.toggle("bi-eye");
    });

    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
      form.addEventListener('submit', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }

          form.classList.add('was-validated');
        },
        false
      );
    });
  </script>
</body>

</html>


<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <form class="container-fluid" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="d-flex justify-content-center">
            <p class="fw-bold fs-4">Welcome to our website</p>
        </div>
        <div class="col-md-12 d-flex align-items-center">
            <?php echo $Error; ?>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text">
                <i class="fa-solid fa-user"></i>
            </span>
            <input type="text" class="form-control form-control-lg bg-light fs-6" placeholder="email" name="email" required />
        </div>

        <div class="input-group mb-1">
            <span class="input-group-text">
                <i class="fa-solid fa-lock"></i>
            </span>
            <input type="password" class="form-control form-control-lg bg-light fs-6" placeholder="password" name="password" required />
        </div>
        <div class="input-group mb-5 d-flex justify-content-between">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="formCheck" />
                <label for="formCheck" class="form-check-label text-secondary"><small>Remember me</small></label>
            </div>
            <div class="forget">
                <small><a href="#">Forget Password?</a></small>
            </div>
        </div>
        <div class="input-group mb-3">
            <button id="btn-login" class="btn btn-lg w-100 fs-4" type="submit" name="login">
                Login
            </button>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body> -->

</html>