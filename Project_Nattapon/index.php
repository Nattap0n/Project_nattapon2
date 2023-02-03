<?php
session_start();
?>

<?php if (!$_SESSION['id'] || !$_SESSION['role']) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบก่อน";
    header('location: login.php');
    exit;
?>
<?php } else {
    $id = $_SESSION['id'];
    $role = $_SESSION['role'];

    require_once "php/conn.php";

    $query = "SELECT * FROM user WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
?>
    <?php
    include_once 'layout/head.php';
    include_once 'view/modal.php';
    ?>

    <body>
        <?php
        include_once 'layout/navbar.php';
        ?>
        <section style="background-color: #5BD1FF;">
            <div class="container py-3 px-5">
                <form class="d-flex" role="search">
                    <input class="form-control me-2 form-control-lg shadow" type="search" placeholder="ค้นหา" aria-label="Search">
                    <button class="btn text-white me-2 shadow" type="submit" style="background-color: #00B7FE;">ค้นหา</button>
                    <button class="btn btn-success me-2 shadow" type="button" data-bs-toggle="modal" data-bs-target="#postModal">โพสต์</button>
                </form>
            </div>
        </section>

        <?php if ($role == 'Member') {
            $page = isset($_GET['page']);
            switch ($page) {
                case "profile":
                    include_once 'view/profile.php';
                    break;
                default:
                    include_once 'view/home.php';
            }
        } else {
            include_once 'view/admin/dashboard.php';
            exit;
        }
        ?>


        <?php
        include_once 'layout/footer.php';
        include_once 'view/alert.php';
        ?>
    </body>

    </html>
<?php } ?>