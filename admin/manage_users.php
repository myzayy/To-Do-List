<?php
require_once '../config/config.php'; // Defines BASE_PATH
session_start();
require_once '../config/connect.php'; // Database connection

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // If not an admin, redirect to the main site index.
    header("Location: " . BASE_PATH . "index.php");
    exit();
}

$current_admin_id = $_SESSION['user_id']; // Get the logged-in admin's ID

// Handle a delete user action
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete_user' && isset($_GET['user_id'])) {
    $user_to_delete_id = intval($_GET['user_id']);

    // Critical check: prevent an admin from deleting their own account
    if ($user_to_delete_id === $current_admin_id) {
        $_SESSION['user_action_error_message'] = 'You cannot delete your own account.';
    } else {
        // Prepare and execute the delete statement
        $stmt_delete = $conn->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt_delete) {
            $stmt_delete->bind_param("i", $user_to_delete_id);
            if ($stmt_delete->execute()) {
                if ($stmt_delete->affected_rows > 0) {
                    $_SESSION['user_action_success_message'] = 'User has been deleted successfully.';
                } else {
                    $_SESSION['user_action_error_message'] = 'User not found or could not be deleted.';
                }
            } else {
                $_SESSION['user_action_error_message'] = 'Error executing deletion: ' . htmlspecialchars($stmt_delete->error);
            }
            $stmt_delete->close();
        } else {
            $_SESSION['user_action_error_message'] = 'Error preparing delete statement: ' . htmlspecialchars($conn->error);
        }
    }
    
    // Redirect back to the user list to show the message and refresh the list
    header("Location: " . BASE_PATH . "admin/manage_users.php");
    exit();
}


// Display messages passed via session (from edit_user.php or from delete action)
$action_message = '';
if (isset($_SESSION['user_action_success_message'])) {
    $action_message = '<div class="alert alert-success" role="alert">' . htmlspecialchars($_SESSION['user_action_success_message']) . '</div>';
    unset($_SESSION['user_action_success_message']);
}
if (isset($_SESSION['user_action_error_message'])) {
    $action_message = '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['user_action_error_message']) . '</div>';
    unset($_SESSION['user_action_error_message']);
}
if (isset($_SESSION['user_action_info_message'])) {
    $action_message = '<div class="alert alert-info" role="alert">' . htmlspecialchars($_SESSION['user_action_info_message']) . '</div>';
    unset($_SESSION['user_action_info_message']);
}

// Fetch all users from the database
$users = [];
$user_list_error = '';
$sql = "SELECT id, username, role, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    $user_list_error = "Error fetching users: " . htmlspecialchars($conn->error);
}
?>
<!DOCTYPE html>
<html lang="uk">
<?php include '../parts/header.php'; ?>
<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>

    <div class="cd-hero">
        <?php include '../parts/navigation.php'; ?>

        <div class="container-fluid tm-page-pad">
            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                    <div class="tm-bg-white-translucent text-xs-left tm-textbox tm-textbox-padding">
                        <h2 class="tm-text-title">Manage Users</h2>
                        <p class="tm-text">
                            This page lists all registered users on the site.
                            <a href="<?php echo BASE_PATH; ?>admin/index.php">Back to Admin Panel</a>
                        </p>
                        
                        <?php echo $action_message; // Display flash messages from session ?>

                        <?php if (!empty($user_list_error)): ?>
                            <div class="alert alert-danger" role="alert"><?php echo $user_list_error; ?></div>
                        <?php elseif (empty($users)): ?>
                            <p class="tm-text">No users found in the database.</p>
                        <?php else: ?>
                            <div class="table-responsive" style="margin-top: 20px;">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                            <th>Registered At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                                <td>
                                                    <span class="badge" style="background-color: <?php echo ($user['role'] == 'admin' ? '#d9534f' : '#6c757d'); ?>; color: white; font-size: 0.9em; padding: 0.5em;">
                                                        <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date("Y-m-d H:i", strtotime($user['created_at'])); ?></td>
                                                <td>
                                                    <?php if ($current_admin_id != $user['id']): // Don't show action buttons for the admin's own account ?>
                                                        <a href="<?php echo BASE_PATH; ?>admin/edit_user.php?user_id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-primary">Edit Role</a>
                                                        
                                                        <a href="<?php echo BASE_PATH; ?>admin/manage_users.php?action=delete_user&user_id=<?php echo $user['id']; ?>" 
                                                           class="btn btn-sm btn-outline-danger" 
                                                           onclick="return confirm('Are you sure you want to delete the user \'<?php echo htmlspecialchars(addslashes($user['username'])); ?>\'? This action cannot be undone.');">Delete</a>
                                                    <?php else: ?>
                                                        <span class="text-muted">(Your Account)</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../parts/footer.php'; ?>
    </div>

    <script src="<?php echo BASE_PATH; ?>js/jquery-1.11.3.min.js"></script>
    <script src="https://www.atlasestateagents.co.uk/javascript/tether.min.js"></script>
    <script src="<?php echo BASE_PATH; ?>js/bootstrap.min.js"></script>
    <script>
        $(window).on('load', function(){
            $('body').addClass('loaded');
            
            $('#tmNavbar .nav-link').on('click', function(){
                if ($('.navbar-toggler').is(':visible') && $('#tmNavbar').hasClass('show')) {
                    $('#tmNavbar').collapse('hide');
                }
            });
        });
    </script>
</body>
</html>