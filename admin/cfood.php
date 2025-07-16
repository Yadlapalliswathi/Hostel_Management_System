<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if (isset($_POST['fsubmit'])) {
    // Sanitize input
    $day = intval($_POST['day']);
    $item1 = htmlspecialchars(trim($_POST['itm1']));
    $item2 = htmlspecialchars(trim($_POST['itm2']));
    $item3 = htmlspecialchars(trim($_POST['itm3']));
    $item4 = htmlspecialchars(trim($_POST['itm4']));
    $item5 = htmlspecialchars(trim($_POST['itm5']));

    if (empty($day) || empty($item1) || empty($item2) || empty($item3) || empty($item4) || empty($item5)) {
        echo "<script>alert('All fields are required. Please fill out the form completely.');</script>";
    } else {
        // Insert into database using prepared statements
        $query = "INSERT INTO foodmenu (day, it1, it2, it3, it4, it5) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);

        if ($stmt) {
            $stmt->bind_param('isssss', $day, $item1, $item2, $item3, $item4, $item5);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "<script>alert('Food items have been added successfully');</script>";
            } else {
                echo "<script>alert('Error: Unable to add food items.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Database error: Unable to prepare the statement.');</script>";
        }
    }
}
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <title>Create Food</title>
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
                <div class="row">
                    <div class="col-md-12">
						<br><br>
                        <h2 class="page-title">Add a Food Item</h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Add Food</div>
                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal">
                                            <div class="hr-dashed"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Select Day</label>
                                                <div class="col-sm-8">
                                                    <select name="day" class="form-control" required>
                                                        <option value="">Select Day</option>
                                                        <option value="1">1st Day</option>
                                                        <option value="2">2nd Day</option>
                                                        <option value="3">3rd Day</option>
                                                        <option value="4">4th Day</option>
                                                        <option value="5">5th Day</option>
                                                        <option value="6">6th Day</option>
                                                        <option value="7">7th Day</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Item No-1</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="itm1" id="itm1" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Item No-2</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="itm2" id="itm2" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Item No-3</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="itm3" id="itm3" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Item No-4</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="itm4" id="itm4" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Item No-5</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="itm5" id="itm5" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-8 col-sm-offset-2">
                                                <input class="btn btn-primary" type="submit" name="fsubmit" value="Create Items">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End of Row -->
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
