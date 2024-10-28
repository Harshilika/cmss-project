<?php include('../includes/config.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Class Timetable</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Student</a></li>
                    <li class="breadcrumb-item active">Timetable</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- Display Timetable for Class 1 -->
        <div class="card">
            <div class="card-body">
                <h3>Timetable for Class 1</h3>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Timing</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Define the time slots
                        $time_slots = [
                            '09:00 AM - 09:45 AM',
                            '09:45 AM - 10:30 AM',
                            '10:30 AM - 11:15 AM',
                            '11:15 AM - 12:00 PM',
                            '12:00 PM - 12:45 PM',
                            '01:00 PM - 01:45 PM',
                            '01:45 PM - 02:30 PM',
                            '02:30 PM - 03:15 PM',
                            '03:15 PM - 04:00 PM'
                        ];

                        // Define days
                        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

                        // Fetch timetable for each time slot
                        foreach ($time_slots as $time_slot) {
                            echo "<tr>";
                            echo "<td>" . $time_slot . "</td>"; // Display time slot

                            // Loop through days to fetch timetable for each day
                            foreach ($days as $day) {
                                $query = mysqli_query($db_conn, "
                                    SELECT * FROM metadata 
                                    WHERE meta_key = 'day_name' 
                                    AND meta_value = '$day' 
                                    AND item_id IN (
                                        SELECT item_id FROM metadata 
                                        WHERE meta_key = 'period_time' 
                                        AND meta_value = '$time_slot'
                                    )
                                ");

                                if (mysqli_num_rows($query) > 0) {
                                    $row = mysqli_fetch_assoc($query);
                                    $subject_id = mysqli_fetch_assoc(mysqli_query($db_conn, "SELECT meta_value FROM metadata WHERE meta_key = 'subject_id' AND item_id = '{$row['item_id']}'"))['meta_value'];
                                    $subject = mysqli_fetch_assoc(mysqli_query($db_conn, "SELECT * FROM posts WHERE id = '$subject_id'"));
                                    echo "<td>" . $subject['title'] . "</td>";
                                } else {
                                    echo "<td>Unscheduled</td>";
                                }
                            }

                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div><!--/. container-fluid -->
</section>
<!-- /.content -->

<?php include('footer.php') ?>
