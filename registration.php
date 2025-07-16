<?php
session_start();
include('includes/config.php');

if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $contactno = $_POST['contact'];
    $emailid = $_POST['email'];
    
    // Validate email
    if (!filter_var($emailid, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!');</script>";
        return;
    }

    // Validate passwords
    if ($_POST['password'] !== $_POST['cpassword']) {
        echo "<script>alert('Passwords do not match!');</script>";
        return;
    }

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password

    try {
        // Check if email already exists
        $checkEmail = $mysqli->prepare("SELECT * FROM userregistration WHERE email = ?");
        if (!$checkEmail) {
            throw new Exception("Prepare failed: " . $mysqli->error);
        }
        $checkEmail->bind_param("s", $emailid);
        $checkEmail->execute();
        $result = $checkEmail->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Email already registered!');</script>";
        } else {
            // Proceed with insertion
            $stmt = $mysqli->prepare("INSERT INTO userregistration (firstName, lastName, gender, contactNo, email, password) VALUES (?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $mysqli->error);
            }
            $stmt->bind_param("ssssss", $fname, $lname, $gender, $contactno, $emailid, $password);
            $stmt->execute();

            echo "<script>alert('Student successfully registered');</script>";
        }

        $checkEmail->close();
        $stmt->close();
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
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
        if (document.registration.password.value != document.registration.cpassword.value) {
            alert("Password and Re-Type Password Field do not match!!");
            document.registration.cpassword.focus();
            return false;
        }
        return true;
    }

    function checkAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data: 'emailid=' + $("#email").val(),
            type: "POST",
            success: function(data) {
                $("#user-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error: function() {
                alert('Error checking availability.');
            }
        });
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
                        <h2 class="page-title">User Registration</h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">Fill all Info</div>
                                    <div class="panel-body">
                                        <form method="post" action="" name="registration" class="form-horizontal" onSubmit="return valid();">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">First Name: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="fname" id="fname" class="form-control" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Last Name: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="lname" id="lname" class="form-control" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Gender: </label>
                                                <div class="col-sm-8">
                                                    <select name="gender" class="form-control" required="required">
                                                        <option value="">Select Gender</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                        <option value="others">Other</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Contact No: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="contact" id="contact" class="form-control" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Email id: </label>
                                                <div class="col-sm-8">
                                                    <input type="email" name="email" id="email" class="form-control" onBlur="checkAvailability()" required="required">
                                                    <span id="user-availability-status" style="font-size:12px;"></span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Password: </label>
                                                <div class="col-sm-8">
                                                    <input type="password" name="password" id="password" class="form-control" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Confirm Password: </label>
                                                <div class="col-sm-8">
                                                    <input type="password" name="cpassword" id="cpassword" class="form-control" required="required">
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-sm-offset-4">
                                                <button class="btn btn-default" type="button" onclick="window.location='index.php'">Cancel</button>
                                                <input type="submit" name="submit" value="Register" class="btn btn-primary">
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

    <?php include 'footer.php'; ?>

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
