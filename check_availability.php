<?php
require_once("includes/config.php");

if (!empty($_POST["emailid"])) {
    $email = $_POST["emailid"];
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        echo "error: You did not enter a valid email.";
    } else {
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM userRegistration WHERE email=?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo "<span style='color:red'>Email already exists.</span>";
        } else {
            echo "<span style='color:green'>Email available for registration.</span>";
        }
    }
}

if (!empty($_POST["oldpassword"])) {
    $pass = $_POST["oldpassword"];
    $stmt = $mysqli->prepare("SELECT password FROM userRegistration WHERE id = ?"); // Assuming you need to identify the user
    $userId = 1; // Replace with actual user ID
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($pass, $hashedPassword)) {
        echo "<span style='color:green'>Password matched.</span>";
    } else {
        echo "<span style='color:red'>Password not matched.</span>";
    }
}

if (!empty($_POST["roomno"])) {
    $roomno = $_POST["roomno"];
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM registration WHERE roomno=?");
    $stmt->bind_param('i', $roomno);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "<span style='color:red'>$count. Seats already full.</span>";
    } else {
        echo "<span style='color:green'>All seats are available.</span>";
    }
}
?>
