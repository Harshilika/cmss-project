<?php include('../includes/config.php'); ?>

<?php
// Handle form submission
if (isset($_POST['submit'])) {
    // Retrieve and sanitize user inputs
    $student_name = isset($_POST['student_name']) ? mysqli_real_escape_string($db_conn, $_POST['student_name']) : '';
    $student_email = isset($_POST['student_email']) ? mysqli_real_escape_string($db_conn, $_POST['student_email']) : ''; // Student email
    $roll_id = isset($_POST['roll_id']) ? mysqli_real_escape_string($db_conn, $_POST['roll_id']) : ''; // Roll ID
    $total_marks_obtained = isset($_POST['total_marks_obtained']) ? mysqli_real_escape_string($db_conn, $_POST['total_marks_obtained']) : '';
    $total_marks = isset($_POST['total_marks']) ? mysqli_real_escape_string($db_conn, $_POST['total_marks']) : '';
    $exam_name = isset($_POST['exam_name']) ? mysqli_real_escape_string($db_conn, $_POST['exam_name']) : ''; // Exam type from dropdown

    $status = 'publish'; // Result is published after being posted
    $type = 'result';
    $date_add = date('Y-m-d g:i:s');

    // Insert student result into the posts table
    $query = mysqli_query($db_conn, "INSERT INTO `posts` (`title`, `status`, `publish_date`, `type`) VALUES ('$student_name', '$status', '$date_add', '$type')");

    if ($query) {
        $item_id = mysqli_insert_id($db_conn);

        // Insert metadata for result details
        mysqli_query($db_conn, "INSERT INTO `metadata` (`meta_key`, `meta_value`, `item_id`) VALUES ('total_marks_obtained', '$total_marks_obtained', '$item_id')");
        mysqli_query($db_conn, "INSERT INTO `metadata` (`meta_key`, `meta_value`, `item_id`) VALUES ('total_marks', '$total_marks', '$item_id')");
        mysqli_query($db_conn, "INSERT INTO `metadata` (`meta_key`, `meta_value`, `item_id`) VALUES ('exam_name', '$exam_name', '$item_id')"); // Store exam type
        mysqli_query($db_conn, "INSERT INTO `metadata` (`meta_key`, `meta_value`, `item_id`) VALUES ('student_email', '$student_email', '$item_id')"); // Store student email
        mysqli_query($db_conn, "INSERT INTO `metadata` (`meta_key`, `meta_value`, `item_id`) VALUES ('roll_id', '$roll_id', '$item_id')"); // Store roll ID

        // Redirect after successful insertion
        header('Location: results.php');
        exit; // Always exit after a header redirect
    } else {
        echo "Error: " . mysqli_error($db_conn); // Display error if the query fails
    }
}
?>

<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Admin - Post Student Results (Exam-wise)</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Post Results</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class='col-lg-8'>
                <!-- Info boxes -->
                <div class="card">
                    <div class="card-header py-2">
                        <h3 class="card-title">Posted Results</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive bg-white">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Student Name</th>
                                        <th>Roll ID</th> <!-- Display roll ID in table -->
                                        <th>Total Marks Obtained</th>
                                        <th>Total Marks</th>
                                        <th>Exam Name</th> <!-- Display exam name in table -->
                                        <th>Student Email</th> <!-- Display student email in table -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    $args = array(
                                        'type' => 'result',
                                        'status' => 'publish',
                                    );
                                    $results = get_posts($args);
                                    
                                    foreach ($results as $result) {
                                        // Fetch metadata safely
                                        $total_marks_obtained = get_metadata($result->id, 'total_marks_obtained');
                                        $total_marks = get_metadata($result->id, 'total_marks');
                                        $exam_name = get_metadata($result->id, 'exam_name');
                                        $student_email = get_metadata($result->id, 'student_email');
                                        $roll_id = get_metadata($result->id, 'roll_id'); // Fetch roll ID

                                        // Use the null coalescing operator to provide a default value if the metadata doesn't exist
                                        $total_marks_obtained_value = $total_marks_obtained[0]->meta_value ?? 'N/A'; // Default value if not set
                                        $total_marks_value = $total_marks[0]->meta_value ?? 'N/A'; // Default value if not set
                                        $exam_name_value = $exam_name[0]->meta_value ?? 'N/A'; // Default value if not set
                                        $student_email_value = $student_email[0]->meta_value ?? 'N/A'; // Default value if not set
                                        $roll_id_value = $roll_id[0]->meta_value ?? 'N/A'; // Default value if not set
                                        ?>
                                        <tr>
                                            <td><?= $count++ ?></td>
                                            <td><?= htmlspecialchars($result->title) ?></td>
                                            <td><?= htmlspecialchars($roll_id_value) ?></td> <!-- Display roll ID -->
                                            <td><?= htmlspecialchars($total_marks_obtained_value) ?></td>
                                            <td><?= htmlspecialchars($total_marks_value) ?></td>
                                            <td><?= htmlspecialchars($exam_name_value) ?></td>
                                            <td><?= htmlspecialchars($student_email_value) ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Add New Result -->
                <div class="card">
                    <div class="card-header py-2">
                        <h3 class="card-title">Add New Result</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="student_name">Student Name</label>
                                <input type="text" name="student_name" placeholder="Student Name" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="student_email">Student Email</label>
                                <input type="email" name="student_email" placeholder="Student Email" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="roll_id">Roll ID</label> <!-- New Roll ID field -->
                                <input type="text" name="roll_id" placeholder="Roll ID" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="total_marks_obtained">Total Marks Obtained</label>
                                <input type="number" name="total_marks_obtained" placeholder="Total Marks Obtained" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="total_marks">Total Marks</label>
                                <input type="number" name="total_marks" placeholder="Total Marks" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="exam_name">Exam Name</label>
                                <select name="exam_name" class="form-control" required> <!-- Dropdown for specific exams -->
                                    <option value="" disabled selected>Select Exam</option>
                                    <option value="FA1">FA1</option>
                                    <option value="FA2">FA2</option>
                                    <option value="SA1">SA1</option>
                                    <option value="FA3">FA3</option>
                                    <option value="FA4">FA4</option>
                                    <option value="Final">Final</option>
                                </select>
                            </div>
                            <button name="submit" class="btn btn-success float-right">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->

<?php include('footer.php'); ?>
