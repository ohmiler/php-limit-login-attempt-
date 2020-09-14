<?php 
    session_start();
    include('config.php');

    $msg = '';

    if (isset($_POST['submit'])) {
        $time = time() - 30;
        $ip_address = getIpAddress();

        // Getting total count of hits on the basis IP
        $query = mysqli_query($dbcon, "SELECT count(*) as total_count FROM loginlogs WHERE TryTime > $time AND IpAddress = '$ip_address' ");
        $check_login_row = mysqli_fetch_assoc($query);
        $total_count = $check_login_row['total_count'];

        if ($total_count == 3) {
            $msg = "To many failed login attempts. Please login after 30s";
        } else {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $res = mysqli_query($dbcon, "SELECT * FROM users WHERE username = '$username' AND password ='$password'");

            if (mysqli_num_rows($res)) {
                $_SESSION['IS_LOGIN'] = "yes";
                $_SESSION['username'] = $username;

                mysqli_query($dbcon, "DELETE FROM loginlogs WHERE IpAddress = '$ip_address' ");
                echo "<script>window.location.href='dashboard.php';</script>";
            } else {
                $total_count++;
                $rem_attm = 3 - $total_count;

                if ($rem_attm == 0) {
                    $msg = "To many failed login attempts. Please login after 30s";
                } else {
                    $msg = "Please enter valid login details. <br>$rem_attm attemps remaining";
                }
                $try_time = time();
                mysqli_query($dbcon, "INSERT INTO loginlogs(IpAddress, TryTime) VALUES('$ip_address', '$try_time')");
            }
        }
    }


    function getIpAddress() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddr = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARD_FOR'])) {
            $ipAddr = $_SERVER['HTTP_X_FORWARD_FOR'];
        } else {
            $ipAddr = $_SERVER['REMOTE_ADDR'];
        }
        return $ipAddr;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Limit Login</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
</head>
<body>
    

    <div class="container">
        <h1 class="display-4 mt-4">Login Page</h1>
        <hr>
        <form action="" id="login-form" method="post">
            <div class="form-group">
                <label for="username" class="text-info">Username:</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password" class="text-info">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group mt-1">
                <input type="submit" name="submit" class="btn btn-info btn-md" value="Submit">
            </div>
            <div id="result"><?php echo $msg; ?></div>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</body>
</html>