<?php include('../includes/config.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">View Student Attendance</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Search Attendance by Student ID</h3>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="student_id">Enter Student ID:</label>
                        <input type="text" name="student_id" class="form-control" required>
                    </div>
                    <button type="submit" name="search" class="btn btn-primary">Search</button>
                </form>
                <hr>

                <?php
                // Check if the search form is submitted
                if (isset($_POST['search'])) {
                    // Get the student ID from the form
                    $student_id = mysqli_real_escape_string($db_conn, $_POST['student_id']);
                    
                    // Query to retrieve attendance records for the specified student ID
                    $sql = "SELECT attendance_month, attendance_value, modified_date FROM `attendance` WHERE `std_id` = '$student_id'";
                    $result = mysqli_query($db_conn, $sql);

                    // Display the attendance records if found
                    if (mysqli_num_rows($result) > 0) {
                        echo "<table class='table table-bordered mt-4'>";
                        echo "<thead><tr><th>Month</th><th>Attendance</th><th>Last Modified</th></tr></thead><tbody>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['attendance_month']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['attendance_value']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['modified_date']) . "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "<div class='alert alert-info mt-4'>No attendance records found for Student ID: " . htmlspecialchars($student_id) . ".</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?php include('footer.php') ?>
