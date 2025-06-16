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

$action_message = ''; // To store feedback messages for the admin

// Handle a delete task action (for admins)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete_task' && isset($_GET['task_id'])) {
    
    $task_to_delete_id = intval($_GET['task_id']);

    // Prepare and execute the delete statement.
    // Note: Admin can delete any task, so there is NO "AND user_id = ?" check.
    $stmt_delete = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    if ($stmt_delete) {
        $stmt_delete->bind_param("i", $task_to_delete_id);
        if ($stmt_delete->execute()) {
            if ($stmt_delete->affected_rows > 0) {
                $_SESSION['admin_task_action_success_message'] = 'Task has been deleted successfully.';
            } else {
                $_SESSION['admin_task_action_error_message'] = 'Task not found or could not be deleted.';
            }
        } else {
            $_SESSION['admin_task_action_error_message'] = 'Error executing deletion: ' . htmlspecialchars($stmt_delete->error);
        }
        $stmt_delete->close();
    } else {
        $_SESSION['admin_task_action_error_message'] = 'Error preparing delete statement: ' . htmlspecialchars($conn->error);
    }
    
    // Redirect back to the task list to show the message and refresh the list
    header("Location: " . BASE_PATH . "admin/manage_tasks.php");
    exit();
}


// Display messages passed via session
if (isset($_SESSION['admin_task_action_success_message'])) {
    $action_message = '<div class="alert alert-success" role="alert">' . htmlspecialchars($_SESSION['admin_task_action_success_message']) . '</div>';
    unset($_SESSION['admin_task_action_success_message']);
}
if (isset($_SESSION['admin_task_action_error_message'])) {
    $action_message = '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['admin_task_action_error_message']) . '</div>';
    unset($_SESSION['admin_task_action_error_message']);
}

// Fetch all tasks from all users, joining with the users table to get usernames
$all_tasks = [];
$task_list_error = '';
$sql = "SELECT
            tasks.id,
            tasks.title,
            tasks.status,
            tasks.created_at,
            tasks.due_date,
            users.username
        FROM tasks
        JOIN users ON tasks.user_id = users.id
        ORDER BY tasks.created_at DESC";

$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $all_tasks[] = $row;
    }
} else {
    $task_list_error = "Error fetching tasks: " . htmlspecialchars($conn->error);
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
                <div class="col-md-12">
                    <div class="tm-bg-white-translucent text-xs-left tm-textbox tm-textbox-padding">
                        <h2 class="tm-text-title">Manage All Tasks</h2>
                        <p class="tm-text">
                            This page lists all tasks from all users on the site.
                            <a href="<?php echo BASE_PATH; ?>admin/index.php">Back to Admin Panel</a>
                        </p>

                        <?php echo $action_message; // Display flash messages from session ?>
                        
                        <?php if (!empty($task_list_error)): ?>
                            <div class="alert alert-danger" role="alert"><?php echo $task_list_error; ?></div>
                        <?php elseif (empty($all_tasks)): ?>
                            <p class="tm-text">No tasks found in the database.</p>
                        <?php else: ?>
                            <div class="table-responsive" style="margin-top: 20px;">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Owner (Username)</th>
                                            <th>Created At</th>
                                            <th>Due Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($all_tasks as $task): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($task['id']); ?></td>
                                                <td><?php echo htmlspecialchars($task['title']); ?></td>
                                                <td>
                                                    <span class="badge" style="background-color: <?php echo ($task['status'] == 'completed' ? '#28a745' : '#6c757d'); ?>; color: white; font-size: 0.9em; padding: 0.5em;">
                                                        <?php echo htmlspecialchars(ucfirst($task['status'])); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($task['username']); ?></td>
                                                <td><?php echo date("Y-m-d H:i", strtotime($task['created_at'])); ?></td>
                                                <td><?php echo htmlspecialchars($task['due_date'] ? date("Y-m-d", strtotime($task['due_date'])) : 'N/A'); ?></td>
                                                <td>
                                                    <a href="<?php echo BASE_PATH; ?>admin/edit_task.php?task_id=<?php echo $task['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                                    
                                                    <!-- Functional Delete link for admin -->
                                                    <a href="<?php echo BASE_PATH; ?>admin/manage_tasks.php?action=delete_task&task_id=<?php echo $task['id']; ?>" 
                                                       class="btn btn-sm btn-outline-danger" 
                                                       onclick="return confirm('Are you sure you want to delete this task (ID: <?php echo $task['id']; ?>)? This action belongs to user \'<?php echo htmlspecialchars(addslashes($task['username'])); ?>\'.');">Delete</a>
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