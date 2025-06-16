<?php
require_once '../config/config.php'; // Defines BASE_PATH
session_start();
require_once '../config/connect.php'; // Database connection

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_PATH . "index.php");
    exit();
}

$task_to_edit_id = null;
$task_to_edit = null; // Variable to hold task details
$action_message = ''; // For displaying messages on this page

// Get task_id from URL parameter
if (isset($_GET['task_id'])) {
    $task_to_edit_id = intval($_GET['task_id']);
} else {
    // Redirect if no task_id is provided
    $_SESSION['admin_task_action_error_message'] = 'No task ID provided.';
    header("Location: " . BASE_PATH . "admin/manage_tasks.php");
    exit();
}

// Handle form submission to update the task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_task'])) {
    $submitted_task_id = intval($_POST['task_id']);
    $title = trim($_POST['task_title']);
    $description = isset($_POST['task_description']) ? trim($_POST['task_description']) : null;
    $due_date = !empty($_POST['task_due_date']) ? trim($_POST['task_due_date']) : null;
    $status = trim($_POST['task_status']);

    // Validate inputs
    $valid_statuses = ['pending', 'completed', 'in_progress'];
    if (empty($title)) {
        $action_message = '<div class="alert alert-danger" role="alert">Task title cannot be empty.</div>';
    } elseif (!in_array($status, $valid_statuses)) {
        $action_message = '<div class="alert alert-danger" role="alert">Invalid status selected.</div>';
    } elseif ($submitted_task_id !== $task_to_edit_id) {
        $action_message = '<div class="alert alert-danger" role="alert">Task ID mismatch.</div>';
    } else {
        // Prepare and execute the update statement.
        // As an admin, we do not check for user_id here.
        $stmt_update = $conn->prepare("UPDATE tasks SET title = ?, description = ?, due_date = ?, status = ? WHERE id = ?");
        if ($stmt_update) {
            $stmt_update->bind_param("ssssi", $title, $description, $due_date, $status, $task_to_edit_id);
            if ($stmt_update->execute()) {
                $_SESSION['admin_task_action_success_message'] = "Task (ID: $task_to_edit_id) has been updated successfully.";
                header("Location: " . BASE_PATH . "admin/manage_tasks.php");
                exit();
            } else {
                $action_message = '<div class="alert alert-danger" role="alert">Error updating task: ' . htmlspecialchars($stmt_update->error) . '</div>';
            }
            $stmt_update->close();
        } else {
             $action_message = '<div class="alert alert-danger" role="alert">Error preparing update statement: ' . htmlspecialchars($conn->error) . '</div>';
        }
    }
}

// Fetch the task details to pre-fill the form
// Join with users table to also display the task owner's username
$stmt_fetch = $conn->prepare("SELECT t.id, t.title, t.description, t.status, DATE_FORMAT(t.due_date, '%Y-%m-%d') AS due_date_formatted, u.username 
                             FROM tasks t 
                             JOIN users u ON t.user_id = u.id 
                             WHERE t.id = ?");
if ($stmt_fetch) {
    $stmt_fetch->bind_param("i", $task_to_edit_id);
    $stmt_fetch->execute();
    $result = $stmt_fetch->get_result();
    if ($result->num_rows === 1) {
        $task_to_edit = $result->fetch_assoc();
    } else {
        $_SESSION['admin_task_action_error_message'] = 'Task not found.';
        header("Location: " . BASE_PATH . "admin/manage_tasks.php");
        exit();
    }
    $stmt_fetch->close();
} else {
    $action_message = '<div class="alert alert-danger" role="alert">Error fetching task details: ' . htmlspecialchars($conn->error) . '</div>';
    $task_to_edit = null; // Ensure form is not displayed on error
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
                <div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <div class="tm-bg-white-translucent text-xs-left tm-textbox tm-textbox-padding">
                        <h2 class="tm-text-title">Admin: Edit Task</h2>
                        
                        <?php echo $action_message; // Display messages from update attempt ?>
                        
                        <?php if ($task_to_edit): // Only show form if task data was successfully fetched ?>
                            <p class="tm-text">
                                You are editing task #<?php echo htmlspecialchars($task_to_edit['id']); ?> 
                                owned by user: <strong><?php echo htmlspecialchars($task_to_edit['username']); ?></strong>
                            </p>

                            <form method="POST" action="<?php echo BASE_PATH; ?>admin/edit_task.php?task_id=<?php echo $task_to_edit['id']; ?>" class="tm-contact-form">
                                <input type="hidden" name="task_id" value="<?php echo $task_to_edit['id']; ?>">
                                
                                <div class="form-group">
                                    <label for="task_title">Title <span style="color:red;">*</span></label>
                                    <input type="text" id="task_title" name="task_title" class="form-control" placeholder="Enter task title" 
                                           value="<?php echo htmlspecialchars($task_to_edit['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="task_description">Description</label>
                                    <textarea id="task_description" name="task_description" class="form-control" rows="5" 
                                              placeholder="Enter task description"><?php echo htmlspecialchars($task_to_edit['description']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="task_due_date">Due Date</label>
                                    <input type="date" id="task_due_date" name="task_due_date" class="form-control"
                                           value="<?php echo htmlspecialchars($task_to_edit['due_date_formatted']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="task_status">Status</label>
                                    <select id="task_status" name="task_status" class="form-control">
                                        <option value="pending" <?php echo ($task_to_edit['status'] == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                        <option value="completed" <?php echo ($task_to_edit['status'] == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                        <option value="in_progress" <?php echo ($task_to_edit['status'] == 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                                    </select>
                                </div>
                                
                                <button type="submit" name="update_task" class="btn btn-primary tm-submit-btn">Update Task</button>
                                <a href="<?php echo BASE_PATH; ?>admin/manage_tasks.php" class="btn btn-secondary tm-submit-btn" style="background-color: #6c757d; margin-left: 10px;">Cancel</a>
                            </form>
                        <?php else: ?>
                            <p class="tm-text">Could not load task details for editing.</p>
                            <a href="<?php echo BASE_PATH; ?>admin/manage_tasks.php" class="btn btn-primary tm-submit-btn">Back to Task List</a>
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