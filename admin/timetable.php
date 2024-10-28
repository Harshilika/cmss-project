<?php include('../includes/config.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Manage Time Table</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Time Table</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <?php
        // Handle form submission
        if(isset($_POST['submit'])) {
            $time_slot = isset($_POST['time_slot']) ? $_POST['time_slot'] : '';
            $day_name = isset($_POST['day_name']) ? $_POST['day_name'] : '';
            $subject_id = isset($_POST['subject_id']) ? $_POST['subject_id'] : '';
            $class_id = 1;  // Fixed class (class_id = 1)
            
            // Insert the timetable entry into the database
            $query = mysqli_query($db_conn, "INSERT INTO metadata (item_id, meta_key, meta_value) VALUES 
                ('$class_id', 'period_time', '$time_slot'),
                ('$class_id', 'day_name', '$day_name'),
                ('$class_id', 'subject_id', '$subject_id')
            ");
            if ($query) {
                echo '<div class="alert alert-success">Timetable entry added successfully!</div>';
            } else {
                echo '<div class="alert alert-danger">Failed to add timetable entry!</div>';
            }
        }
        ?>

        <!-- Admin Form to Add Timetable Entry -->
        <div class="card">
            <div class="card-body">
                <h3>Add Timetable Entry</h3>
                <form action="" method="post">
                    <div class="row">
                        <!-- Time Slot -->
                        <div class="col-lg">
                            <div class="form-group">
                                <label for="time_slot">Select Time Slot</label>
                                <select name="time_slot" id="time_slot" class="form-control">
                                    <option value="">-Select Time Slot-</option>
                                    <option value="09:00 AM - 09:45 AM">09:00 AM - 09:45 AM</option>
                                    <option value="09:45 AM - 10:30 AM">09:45 AM - 10:30 AM</option>
                                    <option value="10:30 AM - 11:15 AM">10:30 AM - 11:15 AM</option>
                                    <option value="11:15 AM - 12:00 PM">11:15 AM - 12:00 PM</option>
                                    <option value="12:00 PM - 12:45 PM">12:00 PM - 12:45 PM</option>
                                    <option value="01:00 PM - 01:45 PM">01:00 PM - 01:45 PM</option>
                                    <option value="01:45 PM - 02:30 PM">01:45 PM - 02:30 PM</option>
                                    <option value="02:30 PM - 03:15 PM">02:30 PM - 03:15 PM</option>
                                    <option value="03:15 PM - 04:00 PM">03:15 PM - 04:00 PM</option>
                                </select>
                            </div>
                        </div>

                        <!-- Day -->
                        <div class="col-lg">
                            <div class="form-group">
                                <label for="day_name">Select Day</label>
                                <select name="day_name" id="day_name" class="form-control">
                                    <option value="">-Select Day-</option>
                                    <option value="monday">Monday</option>
                                    <option value="tuesday">Tuesday</option>
                                    <option value="wednesday">Wednesday</option>
                                    <option value="thursday">Thursday</option>
                                    <option value="friday">Friday</option>
                                    <option value="saturday">Saturday</option>
                                </select>
                            </div>
                        </div>

                        <!-- Subject -->
                        <div class="col-lg">
                            <div class="form-group">
                                <label for="subject_id">Select Subject</label>
                                <select name="subject_id" id="subject_id" class="form-control">
                                    <option value="">-Select Subject-</option>
                                    <option value="19">Mathematics</option>
                                    <option value="20">English</option>
                                    <option value="21">Science</option>
                                    <option value="22">History</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg">
                            <div class="form-group">
                                <label for="">&nbsp;</label>
                                <input type="submit" value="Submit" name="submit" class="btn btn-success form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

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
