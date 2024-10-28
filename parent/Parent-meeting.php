<?php
include('../includes/config.php');

// Handle form submission for sending a message
if (isset($_POST['send_message'])) {
    $sender_id = $_SESSION['user_id']; // Assuming you store the logged-in parent's user ID in session
    $receiver_id = 1; // Admin ID (this should be dynamic based on your requirement)
    $message = mysqli_real_escape_string($db_conn, $_POST['message']);

    $query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$sender_id', '$receiver_id', '$message')";
    mysqli_query($db_conn, $query);
}

// Fetch messages sent by the parent
$parent_id = $_SESSION['user_id']; // Get the parent's ID from the session
$query = "SELECT * FROM messages WHERE sender_id = '$parent_id' OR receiver_id = '$parent_id' ORDER BY created_at DESC";
$result = mysqli_query($db_conn, $query);

include('header.php');
?>

<div class="container-fluid">
    <h1>Send Message to Admin</h1>
    <form action="" method="POST">
        <div class="form-group">
            <label for="message">Message</label>
            <textarea name="message" class="form-control" required></textarea>
        </div>
        <button name="send_message" class="btn btn-primary">Send Message</button>
    </form>

    <h3>Your Messages</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Message</th>
                <th>Status</th>
                <th>Sent At</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['message']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
