<?php

    $url = $domain . 'admin/index.php';

    if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] != "") {
        $id = $_SESSION['admin_id'];
        $select = mysqli_query($connection, "SELECT * FROM `admin` WHERE `id`=$id");
        if (mysqli_num_rows($select)) {
            while ($row = mysqli_fetch_assoc($select)) {

                $id = $row['id'];
                $name = $row['name'];
                $email = $row['email'];
                $password = $row['password'];

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

?>
