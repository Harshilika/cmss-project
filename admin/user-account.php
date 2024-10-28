<?php 
session_start();
include('../includes/config.php');

$error = '';
if (isset($_POST['submit'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash('1234567890', PASSWORD_DEFAULT); // Default password
    $type     = trim($_POST['type']);

    // Validate inputs
    if (empty($name) || empty($email) || empty($type)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        // Check for existing email
        $check_query = $db_conn->prepare("SELECT * FROM accounts WHERE email = ?");
        $check_query->bind_param("s", $email);
        $check_query->execute();
        $result = $check_query->get_result();

        if ($result->num_rows > 0) {
            $error = 'Email already exists';
        } else {
            // Insert new account
            $insert_query = $db_conn->prepare("INSERT INTO accounts (name, email, password, type) VALUES (?, ?, ?, ?)");
            $insert_query->bind_param("ssss", $name, $email, $password, $type);

            if ($insert_query->execute()) {
                $_SESSION['success_msg'] = 'User has been successfully registered';
                header('location: user-account.php?user=' . $type);
                exit;
            } else {
                $error = 'Error registering user: ' . $db_conn->error;
            }
        }
    }
}
?>

<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Manage Accounts</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Accounts</a></li>
                    <li class="breadcrumb-item active">
                        <?php echo ucfirst(isset($_REQUEST['user']) ? $_REQUEST['user'] : ''); ?>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<section class="content">
    <div class="container-fluid">
        <?php if (isset($_GET['action']) && $_GET['action'] == 'add-new'): ?>
            <div class="card">
                <div class="card-body">
                    <form action="" method="post">
                        <fieldset>
                            <legend>Student Information</legend>
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <input type="hidden" name="type" value="<?php echo htmlspecialchars(isset($_REQUEST['user']) ? $_REQUEST['user'] : ''); ?>">
                            <button type="submit" name="submit" class="btn btn-primary">Register</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-header py-2">
                    <h3 class="card-title">
                        <?php echo ucfirst(isset($_REQUEST['user']) ? $_REQUEST['user'] : ''); ?>s
                    </h3>
                    <div class="card-tools">
                        <a href="?user=<?php echo htmlspecialchars(isset($_REQUEST['user']) ? $_REQUEST['user'] : ''); ?>&action=add-new" class="btn btn-success btn-xs"><i class="fa fa-plus mr-2"></i>Add New</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive bg-white">
                        <table class="table table-bordered" id="users-table" width="100%">
                            <thead>
                                <tr>
                                    <th width="50">ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    jQuery(document).ready(function() {
        jQuery('#users-table').DataTable({
            ajax: {
                url: 'ajax.php?user=<?php echo htmlspecialchars(isset($_GET['user']) ? $_GET['user'] : ''); ?>',
                type: 'POST'
            },
            columns: [
                { data: 'serial' },
                { data: 'name' },
                { data: 'email' },
                { data: 'action', orderable: false }
            ],
            processing: true,
            serverSide: true,
        });
    });
</script>

<?php include('footer.php'); ?>
