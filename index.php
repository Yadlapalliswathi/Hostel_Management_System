<?php
session_start();
include('includes/config.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare statement to fetch the hashed password
    $stmt = $mysqli->prepare("SELECT password, id FROM userregistration WHERE email=?");
    if (!$stmt) {
        die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($hashedPassword, $id);
    $stmt->fetch();
    $stmt->close();

    // Verify password
    if ($hashedPassword && password_verify($password, $hashedPassword)) {
        $_SESSION['id'] = $id;
        $_SESSION['login'] = $email;

        $uip = $_SERVER['REMOTE_ADDR'];
        $ldate = date('d/m/Y h:i:s', time());

        // Get user location
        $geopluginURL = 'http://www.geoplugin.net/php.gp?ip=' . $uip;
        $addrDetailsArr = @unserialize(file_get_contents($geopluginURL));

        if ($addrDetailsArr === false) {
            $city = 'Unknown';
            $country = 'Unknown';
        } else {
            $city = $addrDetailsArr['geoplugin_city'] ?? 'Unknown';
            $country = $addrDetailsArr['geoplugin_countryName'] ?? 'Unknown';
        }

        // Log user details
        $log = "INSERT INTO userlog (userId, userEmail, ip_address, city, country) VALUES ( ?,?,?,?,?)";
        $logStmt = $mysqli->prepare($log);
        if (!$logStmt) {
            die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        $logStmt->bind_param('issss', $id, $email, $uip, $city, $country);
        if (!$logStmt->execute()) {
            error_log("Error logging user details: " . $logStmt->error);
        }
        $logStmt->close();

        header("location:dashboard.php");
        exit;
    } else {
        echo "<script>alert('Invalid Username/Email or password');</script>";
    }
}
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <title>User Hostel Registration</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">

    <script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
    <script type="text/javascript" src="js/validation.min.js"></script>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="login-page bk-img" style="background-image: url(img/bd1.jpg);">
                    <div class="row">
                        <div class="col-md-12"><br><br>
                            <h2 class="page-title">User Login</h2>
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="well row pt-2x pb-3x bk-light">
                                        <div class="col-md-8 col-md-offset-2">
                                            <form action="" class="mt" method="post">
                                                <label for="" class="text-uppercase text-sm">Email</label>
                                                <input type="email" placeholder="Email.." name="email" class="form-control mb" required>
                                                <label for="" class="text-uppercase text-sm">Password</label>
                                                <input type="password" placeholder="Password.." name="password" class="form-control mb" required>
                                                <input type="submit" name="login" class="btn btn-primary btn-block" value="Login">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 	
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/fileinput.js"></script>
    <script src="js/chartData.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
