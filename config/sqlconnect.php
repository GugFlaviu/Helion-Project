<?php
$connect = mysqli_connect("localhost", "flaviu", "admin", "helion_db");
if (!$connect) {
    echo "connection error" . mysqli_connect_error();
}
