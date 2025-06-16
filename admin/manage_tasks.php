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

// Fetch all tasks from all users, joining with the users table to get usernames
$all_tasks = [];
$task_list_error = '';

// SQL query with a JOIN to get username for each task
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

// $conn->close();
?>
<!DOCTYPE html>
<html lang="uk">
<?php include '../parts/header.php'; // Path from admin/ directory ?>
<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>

    <div class="cd-hero">
        <?php include '../parts/navigation.php'; // Path from admin/ directory ?>

        <div class="container-fluid tm-page-pad">
            <div class="row">
                <!-- Use a wider column for the table -->
                <div class="col-md-12"> 
                    <div class="tm-bg-white-translucent text-xs-left tm-textbox tm-textbox-padding">
                        <h2 class="tm-text-title">Manage All Tasks</h2>
                        <p class="tm-text">
                            This page lists all tasks from all users on the site.
                            <a href="<?php echo BASE_PATH; ?>admin/index.php">Back to Admin Panel</a>
                        </p>
                        
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
                                                    <!-- Placeholder for Admin Edit/Delete task buttons -->
                                                    <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
                                                    <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
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