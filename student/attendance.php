<?php
session_start();
include('../includes/config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Assuming user_id is stored in session upon login
$std_id = $_SESSION['user_id']; // Get the student ID from session
?>

<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Your Attendance Records</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Student</a></li>
                    <li class="breadcrumb-item active">Attendance</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Attendance Records</h3>
            </div>
            <div class="card-body">
                <?php
                // Fetch attendance records for the logged-in student
                $attendance_query = "SELECT * FROM attendance WHERE std_id = '$std_id'";
                $attendance_result = mysqli_query($db_conn, $attendance_query);

                if (mysqli_num_rows($attendance_result) > 0) {
                    echo "<table class='table table-bordered'>";
                    echo "<thead><tr><th>Month</th><th>Attendance Value</th></tr></thead><tbody>";
                    while ($row = mysqli_fetch_assoc($attendance_result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['attendance_month']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['attendance_value']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<div class='alert alert-info'>No attendance records found.</div>";
                }
                ?>
            </div>
        </div>
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->

<?php include('footer.php') ?>
