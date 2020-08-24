<?php

// connect to a database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// check connection
if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}
