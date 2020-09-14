<?php 

    session_start();
    unset($_SESSION['IS_LOGIN']);
    unset($_SESSION['username']);
    session_destroy();

    echo "<script>window.location.href='index.php'</script>";


?>