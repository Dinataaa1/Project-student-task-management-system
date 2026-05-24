<?php

define('BASE_URL', 'http://localhost/project-student-task-management-system/');

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "lol_db"
);

if (!$conn) {
    die("Koneksi gagal");
}
?>