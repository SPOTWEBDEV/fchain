<?php
$url = $domain . 'auth/login/';

function formatNumber($number)
{
    return number_format($number, 2, '.', ',');
}


function sanitize($value, $default = 'None') {
    return empty(trim($value)) ? $default : $value;
}



if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != "") {
    $id = $_SESSION['user_id'];
    $select = mysqli_query($connection, "SELECT * FROM `users` WHERE `id`=$id");
    if (mysqli_num_rows($select)) {
        while ($row = mysqli_fetch_assoc($select)) {
            $email            = sanitize($row['email']);
            $fullname         = sanitize($row['name']);
            $status           = sanitize($row['status']);
            $balance = $row['mainBalance'];

            
        }
    } else {
        echo "<script>window.open('$url', '_self');</script>";
    }
} else {
    echo "<script>window.open('$url', '_self');</script>";
}



if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_destroy();
    echo "<script>window.open('$url', '_self');</script>";
}
