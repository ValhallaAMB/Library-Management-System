<header>
  <nav class="navbar navbar-expand-lg navbar-dark fs-5">
    <div class="container">
      <a class="navbar-brand fs-4 mb-1">Library</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav ">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" href="dashboard.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="authorTable.php">Authors</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="bookTable.php">Books</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php if ($_SESSION['email'] == "admin@admin.com") { ?>
              librarianTable.php <?php } else {
                                        $_SESSION['error'] = "You do not have permission to access the librarians page.";
                                      } ?>">Librarians</a>
          </li>
        </ul>

        <!-- Log Out Button -->
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="profile.php?id=<?= $_SESSION['id'] ?>">My Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Log Out</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>