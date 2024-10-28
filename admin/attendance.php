<?php include('../includes/config.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Post Student Attendance</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Student</a></li>
                    <li class="breadcrumb-item active">Post Attendance</li>
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
                <h3 class="card-title">Attendance Form</h3>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="std_id">Student ID</label>
                        <input type="text" name="std_id" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="attendance_month">Month</label>
                        <select name="attendance_month" class="form-control" required>
                            <option value="">Select Month</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="attendance_value">Attendance Value (Serialized Array)</label>
                        <textarea name="attendance_value" class="form-control" required></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Post Attendance</button>
                </form>
            </div>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['std_id'], $_POST['attendance_month'], $_POST['attendance_value'])) {
                $std_id = mysqli_real_escape_string($db_conn, $_POST['std_id']);
                $attendance_month = mysqli_real_escape_string($db_conn, $_POST['attendance_month']);
                $attendance_value = mysqli_real_escape_string($db_conn, $_POST['attendance_value']);

                // Check for existing attendance record
                $sql_check = "SELECT * FROM `attendance` WHERE `std_id` = '$std_id' AND `attendance_month` = '$attendance_month'";
                $result_check = mysqli_query($db_conn, $sql_check);

                if (mysqli_num_rows($result_check) > 0) {
                    // Update existing record
                    $sql_update = "UPDATE `attendance` SET `attendance_value` = '$attendance_value' WHERE `std_id` = '$std_id' AND `attendance_month` = '$attendance_month'";
                    mysqli_query($db_conn, $sql_update);
                    echo "<div class='alert alert-success'>Attendance updated successfully!</div>";
                } else {
                    // Insert new record
                    $sql_insert = "INSERT INTO `attendance` (`attendance_month`, `attendance_value`, `std_id`) VALUES ('$attendance_month', '$attendance_value', '$std_id')";
                    mysqli_query($db_conn, $sql_insert);
                    echo "<div class='alert alert-success'>Attendance posted successfully!</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>All fields are required.</div>";
            }
        }
        ?>
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->

<?php include('footer.php') ?>
