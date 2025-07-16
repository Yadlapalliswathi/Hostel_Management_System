<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        // Update email
        $email = filter_var($_POST['emailid'], FILTER_VALIDATE_EMAIL);
        $aid = $_SESSION['id'];
        $udate = date('Y-m-d');

        if ($email) {
            $query = "UPDATE admin SET email=?, updation_date=? WHERE id=?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ssi', $email, $udate, $aid);
            if ($stmt->execute()) {
                echo "<script>alert('Email ID has been successfully updated');</script>";
            } else {
                echo "<script>alert('Error updating email ID. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Invalid email address.');</script>";
        }
    }

    if (isset($_POST['changepwd'])) {
        // Change password
        $op = $_POST['oldpassword'];
        $np = $_POST['newpassword'];
        $cpassword = $_POST['cpassword'];
        $ai = $_SESSION['id'];
        $udate = date('Y-m-d');

        if ($np === $cpassword) {
            $sql = "SELECT password FROM admin WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('i', $ai);
            $stmt->execute();
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            $stmt->close();

            if (password_verify($op, $hashed_password)) {
                $new_hashed_password = password_hash($np, PASSWORD_DEFAULT);
                $update_query = "UPDATE admin SET password=?, updation_date=? WHERE id=?";
                $stmt = $mysqli->prepare($update_query);
                $stmt->bind_param('ssi', $new_hashed_password, $udate, $ai);
                if ($stmt->execute()) {
                    $_SESSION['msg'] = "Password Changed Successfully!";
                } else {
                    $_SESSION['msg'] = "Error changing password. Please try again.";
                }
            } else {
                $_SESSION['msg'] = "Old Password does not match!";
            }
        } else {
            $_SESSION['msg'] = "New Password and Confirm Password do not match!";
        }
    }
}
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Admin Profile</title>
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
    <script type="text/javascript">
        function valid() {
            const newPassword = document.changepwd.newpassword.value;
            const confirmPassword = document.changepwd.cpassword.value;

            if (newPassword !== confirmPassword) {
                alert("Password and Confirm Password do not match!");
                document.changepwd.cpassword.focus();
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12"><br><br>
                        <h2 class="page-title">Admin Profile</h2>
                        <?php
                        $aid = $_SESSION['id'];
                        $ret = "SELECT * FROM admin WHERE id=?";
                        $stmt = $mysqli->prepare($ret);
                        $stmt->bind_param('i', $aid);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        while ($row = $res->fetch_object()) {
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Admin Profile Details</div>
                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Username</label>
                                                <div class="col-sm-10">
                                                    <input type="text" value="<?php echo $row->username; ?>" disabled class="form-control">
                                                    <span class="help-block m-b-none">Username can't be changed.</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Email</label>
                                                <div class="col-sm-10">
                                                    <input type="email" class="form-control" name="emailid" id="emailid" value="<?php echo $row->email; ?>" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Reg Date</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" value="<?php echo $row->reg_date; ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-8 col-sm-offset-2">
                                                <button class="btn btn-default" type="submit">Cancel</button>
                                                <input class="btn btn-primary" type="submit" name="update" value="Update Profile">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Change Password</div>
                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal" name="changepwd" id="change-pwd" onSubmit="return valid();">
                                            <?php if (isset($_SESSION['msg'])) { ?>
                                            <p style="color: red"><?php echo htmlentities($_SESSION['msg']); $_SESSION['msg'] = ""; ?></p>
                                            <?php } ?>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Old Password</label>
                                                <div class="col-sm-8">
                                                    <input type="password" name="oldpassword" id="oldpassword" class="form-control" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">New Password</label>
                                                <div class="col-sm-8">
                                                    <input type="password" class="form-control" name="newpassword" id="newpassword" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Confirm Password</label>
                                                <div class="col-sm-8">
                                                    <input type="password" class="form-control" name="cpassword" id="cpassword" required="required">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-sm-offset-4">
                                                <button class="btn btn-default" type="submit">Cancel</button>
                                                <input type="submit" name="changepwd" value="Change Password" class="btn btn-primary">
                                            </div>
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
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/fileinput.js"></script>
    <script src="js/chartData.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
