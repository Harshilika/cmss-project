<?php include('../includes/config.php'); ?>

<?php
$student_results = []; // Initialize an array to store results
$exam_names = ['FA1', 'FA2', 'SA1', 'FA3', 'FA4', 'Final']; // List of available exam names

// Check if the form is submitted
if (isset($_POST['check_results'])) {
    // Retrieve and sanitize the student's email and exam name
    $student_email = isset($_POST['student_email']) ? mysqli_real_escape_string($db_conn, $_POST['student_email']) : '';
    $exam_name = isset($_POST['exam_name']) ? mysqli_real_escape_string($db_conn, $_POST['exam_name']) : '';

    // Query to get results for the entered email and exam name
    $query = mysqli_query($db_conn, "
        SELECT p.title, m1.meta_value AS total_marks_obtained, m2.meta_value AS total_marks, m3.meta_value AS exam_name 
        FROM posts p 
        INNER JOIN metadata m1 ON p.id = m1.item_id AND m1.meta_key = 'total_marks_obtained' 
        INNER JOIN metadata m2 ON p.id = m2.item_id AND m2.meta_key = 'total_marks' 
        INNER JOIN metadata m3 ON p.id = m3.item_id AND m3.meta_key = 'exam_name' 
        INNER JOIN metadata m4 ON p.id = m4.item_id AND m4.meta_key = 'student_email' AND m4.meta_value = '$student_email' 
        WHERE m3.meta_value = '$exam_name'
    ");

    // Fetch results
    while ($row = mysqli_fetch_assoc($query)) {
        $student_results[] = $row; // Store each result in the array
    }
}
?>

<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Check Your Results</h1>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Enter Your Email and Select Exam to Check Results</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="student_email">Student Email</label>
                                <input type="email" name="student_email" placeholder="Enter your email" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="exam_name">Select Exam</label>
                                <select name="exam_name" class="form-control" required>
                                    <option value="" disabled selected>Select Exam</option>
                                    <?php foreach ($exam_names as $exam): ?>
                                        <option value="<?= htmlspecialchars($exam) ?>"><?= htmlspecialchars($exam) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button name="check_results" class="btn btn-primary float-right">Check Results</button>
                        </form>
                    </div>
                </div>

                <!-- Results Display -->
                <?php if (!empty($student_results)): ?>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Your Exam Results</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive bg-white">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Student Name</th>
                                            <th>Total Marks Obtained</th>
                                            <th>Total Marks</th>
                                            <th>Exam Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 1;
                                        foreach ($student_results as $result): ?>
                                            <tr>
                                                <td><?= $count++ ?></td>
                                                <td><?= htmlspecialchars($result['title']) ?></td>
                                                <td><?= htmlspecialchars($result['total_marks_obtained']) ?></td>
                                                <td><?= htmlspecialchars($result['total_marks']) ?></td>
                                                <td><?= htmlspecialchars($result['exam_name']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php elseif (isset($_POST['check_results'])): ?>
                    <div class="alert alert-warning">No results found for the provided email and exam.</div>
                <?php endif; ?>
            </div>
        </div>
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->

<?php include('footer.php'); ?>
