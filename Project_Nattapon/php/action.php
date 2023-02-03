<?php
session_start();
require_once 'conn.php';

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'register') {
        register();
        exit;
    } elseif ($_POST['action'] == 'login') {
        login();
        exit;
    } elseif ($_POST['action'] == 'post') {
        post();
        exit;
    }elseif ($_POST['action'] == 'uploadImg') {
        uploadImg();
        exit;
    }
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'logout') {
        logout();
        exit;
    }
}



function uploadImg() 
{
    global $conn;

    $targetDir = "../uploads/";
    $fileName = basename($_FILES["post_image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
    move_uploaded_file($_FILES["post_image"]["tmp_name"], $targetFilePath);

    
}



function register()
{
    global $conn;

    $fname = mysqli_real_escape_string($conn, $_POST['inputFname']);
    $lname = mysqli_real_escape_string($conn, $_POST['inputLname']);
    $email = mysqli_real_escape_string($conn, $_POST['inputEmail']);
    $password = mysqli_real_escape_string($conn, $_POST['inputPassword']);
    $std_id = mysqli_real_escape_string($conn, $_POST['inputStd_id']);
    date_default_timezone_set('Asia/Bangkok');
    $date = date("Y-m-d H:i:s");

    $password_hash = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

    if (!empty($fname)) {
        if (!empty($lname)) {
            if (!empty($email)) {
                if (!empty($std_id)) {
                    if (!empty($password)) {


                        // check duplicate data
                        $user_check_query = "SELECT * FROM user WHERE email = '$email' AND std_no = '$std_id'";
                        $query = mysqli_query($conn, $user_check_query);
                        $check = mysqli_fetch_assoc($query);

                        if ($check) {
                            $_SESSION['error'] = "อีเมลล์หรือรหัสนักศึกษานี้มีในระบบแล้ว!";
                            header("Location: ../register.php");
                            exit;
                        } else {
                            $query = "INSERT INTO user (email, pass, fname, lname, std_no, reg_date, status)
                    VALUES ('$email', '$password_hash', '$fname', '$lname', '$std_id', '$date', 'Member')";
                            $result_query =  mysqli_query($conn, $query);
                            if ($result_query) {
                                $_SESSION['success'] = "สมัครสมาชิกสำเร็จ!";
                                header("Location: ../login.php");
                                exit;
                            } else {
                                $_SESSION['error'] = "เกิดข้อผิดพลาด! กรุณาลองอีกครั้ง";
                                header("Location: ../register.php");
                                exit;
                            }
                        }
                    } else {
                        $_SESSION['error'] = "กรุณากรอกพาสเวิร์ด";
                        header("Location: ../register.php");
                        exit;
                    }
                } else {
                    $_SESSION['error'] = "กรุณากรอกเบอร์โทรศัพท์";
                    header("Location: ../register.php");
                    exit;
                }
            } else {
                $_SESSION['error'] = "กรุณากรอกอีเมลล์";
                header("Location: ../register.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "กรุณากรอกนามสกุล";
            header("Location: ../register.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "กรุณากรอกชื่อจริง";
        header("Location: ../register.php?");
        exit;
    }
}

function post()
{
    global $conn;

    $name = mysqli_real_escape_string($conn, $_POST['inputname']);
    $address = mysqli_real_escape_string($conn, $_POST['inputaddress']);
    $userid = mysqli_real_escape_string($conn, $_POST['userid']);
    
    date_default_timezone_set('Asia/Bangkok');
    $date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO post_tbl(post_by, post_topic, post_address, post_date, post_banner) VALUES ('$userid','$name','$address','$date','$img')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: ../index.php");
        exit;
    } else {
        echo mysqli_error($conn);
    }
}


function login()
{
    global $conn;
    date_default_timezone_set('Asia/Bangkok');

    $email = mysqli_real_escape_string($conn, $_POST['inputEmail']);
    $password = mysqli_real_escape_string($conn, $_POST['inputPassword']);

    if (!empty($email)) {
        if (!empty($password)) {
            $user = "SELECT * FROM user WHERE email = '$email'";
            $query = mysqli_query($conn, $user);
            $result = mysqli_fetch_assoc($query);

            if ($result > 0) {
                $stored_pass = $result['pass'];
                if (password_verify($password, $stored_pass)) {
                    $_SESSION['id'] = $result['id'];
                    $_SESSION['role'] = $result['status'];
                    $_SESSION['success'] = "เข้าสู่ระบบสำเร็จ!";
                    header('location: ../index.php');
                    exit;
                } else {
                    $_SESSION['error'] = "รหัสผ่านไม่ถูกต้อง";
                    header('location: ../login.php');
                    exit;
                }
            } else {
                $_SESSION['error'] = "อีเมลล์ไม่ถูกต้อง";
                header('location: ../login.php');
                exit;
            }
        } else {
            $_SESSION['error'] = "กรุณากรอกพาสเวิร์ด";
            header("Location: ../login.php?");
            exit;
        }
    } else {
        $_SESSION['error'] = "กรุณากรอกอีเมลล์";
        header("Location: ../register.php?");
        exit;
    }
}

function logout()
{
    header("Location: ../login.php?success=ออกจากระบบสำเร็จ!");
    session_unset();
    session_destroy();
    exit;
}

