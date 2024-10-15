<?php
require_once("config.php");
$tableNameLibrarians = "librarians";
// Checks if a table by the name $tableNameLibrarians exists
$tableExistsQueryLibrarians = "SHOW TABLES LIKE '$tableNameLibrarians' ";
$resultLibrarians = mysqli_query($con, $tableExistsQueryLibrarians);

if ($resultLibrarians && mysqli_num_rows($resultLibrarians) > 0) {
  // echo "Table $tableNameLibrarians exists. <br>";
} else {
  $createTableQueryLibrarians = "
    CREATE TABLE $tableNameLibrarians (
      librarian_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      first_name VARCHAR(255) NOT NULL,
      last_name VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL,
      pass VARCHAR(255) NOT NULL,
      confirm_pass VARCHAR(255) NOT NULL
    )
  ";

  if (mysqli_query($con, $createTableQueryLibrarians)) {
    // echo "Table $tableNameLibrarians created successfully." . "<br>";
  } else {
    die("Failed creating table: " . mysqli_error($con));
  }
  //REMOVE THIS IF PUBLISHING, ADMIN PAGE CONTAINS ALL Librarians AND ENTRIES.
  $query = "INSERT INTO $tableNameLibrarians (first_name, last_name, email, pass, confirm_pass) VALUES ('admin', 'admin', 'admin@admin.com', 'admin', 'admin')";
  $query_run = mysqli_query($con, $query);
  if (!$query_run) {
    die('Error in query');
  } else {
    echo "admin inserted successfully";
  }
}


$tableNameAuthors = "authors";
// Checks if a table by the name $tableNameAuthors exists
$tableExistsQueryAuthors = "SHOW TABLES LIKE '$tableNameAuthors' ";
$resultAuthors = mysqli_query($con, $tableExistsQueryAuthors);

if ($resultAuthors && mysqli_num_rows($resultAuthors) > 0) {
    // echo "Table $tableNameAuthors exists. <br>";
} else {
    $createTableQueryAuthors = "
    CREATE TABLE $tableNameAuthors (
        author_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        author_name VARCHAR(255) NOT NULL,
        date_of_birth DATE NOT NULL,
        gender VARCHAR(10) NOT NULL,
        bio TEXT NOT NULL
    )
  ";

    if (mysqli_query($con, $createTableQueryAuthors)) {
        // echo "Table $tableNameAuthors created successfully." . "<br>";
    } else {
        die("Failed creating table: " . mysqli_error($con));
    }
}

$tableNameBooks = "books";
// Checks if a table by the name $tableNameBooks exists
$tableExistsQueryBooks = "SHOW TABLES LIKE '$tableNameBooks' ";
$resultBooks = mysqli_query($con, $tableExistsQueryBooks);

if ($resultBooks && mysqli_num_rows($resultBooks) > 0) {
    // echo "Table $tableNameBooks exists. <br>";
} else {
    $createTableQueryBooks = "
    CREATE TABLE $tableNameBooks (
        book_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        publish_date DATE NOT NULL,
        number_of_pages INT UNSIGNED NOT NULL,
        genre VARCHAR(50) NOT NULL,
        author_id INT UNSIGNED NOT NULL,
        FOREIGN KEY (author_id) REFERENCES $tableNameAuthors(author_id)
    )
";


    if (mysqli_query($con, $createTableQueryBooks)) {
        // echo "Table $tableNameBooks created successfully." . "<br>";
    } else {
        die("Failed creating table: " . mysqli_error($con));
    }
}

// Add foreign key relationship between author and books tables
$alterTableQuery = "
  ALTER TABLE $tableNameBooks
  ADD CONSTRAINT fk_author_books
  FOREIGN KEY (author_id) REFERENCES $tableNameAuthors(id)
  ON DELETE CASCADE;
";

if (mysqli_query($con, $alterTableQuery)) {
    // echo "Foreign key relationship added successfully." . "<br>";
} else {
    die("Failed adding foreign key relationship: " . mysqli_error($con));
}
