<?php
require_once 'config/config.php'; // Defines BASE_PATH, ensure this is included
session_start();
require_once 'config/connect.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_PATH . "login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get current user's ID
$task_action_message = ''; // For success/error messages from actions

if (isset($_SESSION['task_action_success_message'])) {
    $task_action_message .= '<div class="alert alert-success" role="alert">' . htmlspecialchars($_SESSION['task_action_success_message']) . '</div>';
    unset($_SESSION['task_action_success_message']);
}
if (isset($_SESSION['task_action_error_message'])) {
    $task_action_message .= '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['task_action_error_message']) . '</div>';
    unset($_SESSION['task_action_error_message']);
}
if (isset($_SESSION['task_action_info_message'])) {
    $task_action_message .= '<div class="alert alert-info" role="alert">' . htmlspecialchars($_SESSION['task_action_info_message']) . '</div>';
    unset($_SESSION['task_action_info_message']);
}
// Handle GET actions: toggle status or delete task
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && isset($_GET['task_id'])) {
    $action = $_GET['action'];
    $task_id = intval($_GET['task_id']); // Sanitize task_id

    if ($action == 'toggle_status') {
        // Toggle task status
        // First, get the current status to ensure the task belongs to the user
        $stmt_current_status = $conn->prepare("SELECT status FROM tasks WHERE id = ? AND user_id = ?");
        if ($stmt_current_status) {
            $stmt_current_status->bind_param("ii", $task_id, $user_id);
            $stmt_current_status->execute();
            $result_current_status = $stmt_current_status->get_result();
            if ($current_task = $result_current_status->fetch_assoc()) {
                $new_status = ($current_task['status'] == 'pending') ? 'completed' : 'pending';
                $stmt_toggle = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
                if ($stmt_toggle) {
                    $stmt_toggle->bind_param("sii", $new_status, $task_id, $user_id);
                    if ($stmt_toggle->execute()) {
                        $task_action_message = '<div class="alert alert-success" role="alert">Task status updated successfully!</div>';
                    } else {
                        $task_action_message = '<div class="alert alert-danger" role="alert">Error updating task status: ' . htmlspecialchars($stmt_toggle->error) . '</div>';
                    }
                    $stmt_toggle->close();
                } else {
                     $task_action_message = '<div class="alert alert-danger" role="alert">Error preparing status update statement: ' . htmlspecialchars($conn->error) . '</div>';
                }
            } else {
                $task_action_message = '<div class="alert alert-danger" role="alert">Task not found or you do not have permission to modify it.</div>';
            }
            $stmt_current_status->close();
        } else {
            $task_action_message = '<div class="alert alert-danger" role="alert">Error preparing to fetch current task status: ' . htmlspecialchars($conn->error) . '</div>';
        }
        // Redirect to clear GET parameters and show message
        header("Location: " . BASE_PATH . "tasks.php" . ($task_action_message ? '?message=' . urlencode(strip_tags($task_action_message)) : ''));
        exit();

    } elseif ($action == 'delete_task') {
        // Delete task
        // Ensure the task belongs to the user before deleting
        $stmt_delete = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        if ($stmt_delete) {
            $stmt_delete->bind_param("ii", $task_id, $user_id);
            if ($stmt_delete->execute()) {
                if ($stmt_delete->affected_rows > 0) {
                    $task_action_message = '<div class="alert alert-success" role="alert">Task deleted successfully!</div>';
                } else {
                    $task_action_message = '<div class="alert alert-warning" role="alert">Task not found or you do not have permission to delete it.</div>';
                }
            } else {
                $task_action_message = '<div class="alert alert-danger" role="alert">Error deleting task: ' . htmlspecialchars($stmt_delete->error) . '</div>';
            }
            $stmt_delete->close();
        } else {
             $task_action_message = '<div class="alert alert-danger" role="alert">Error preparing delete statement: ' . htmlspecialchars($conn->error) . '</div>';
        }
        // Redirect to clear GET parameters and show message
        header("Location: " . BASE_PATH . "tasks.php" . ($task_action_message ? '?message=' . urlencode(strip_tags($task_action_message)) : ''));
        exit();
    }
}

// Display messages passed via GET parameter after redirect
if (isset($_GET['message'])) {
    // Basic security: strip_tags to prevent XSS from URL, though task_action_message is already somewhat controlled
    // For production, a more robust flash message system would be better.
    $task_action_message = '<div class="alert ' . (strpos($_GET['message'], 'successfully') !== false ? 'alert-success' : 'alert-danger') . '" role="alert">' . htmlspecialchars(strip_tags(urldecode($_GET['message']))) . '</div>';
}


// Handle new task submission (CREATE operation) - This logic remains the same
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $title = trim($_POST['task_title']);
    $description = isset($_POST['task_description']) ? trim($_POST['task_description']) : null;
    $due_date = !empty($_POST['task_due_date']) ? trim($_POST['task_due_date']) : null;

    if (empty($title)) {
        $task_action_message = '<div class="alert alert-danger" role="alert">Task title cannot be empty.</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, due_date, status) VALUES (?, ?, ?, ?, 'pending')");
        if ($stmt) {
            $stmt->bind_param("isss", $user_id, $title, $description, $due_date);
            if ($stmt->execute()) {
                $task_action_message = '<div class="alert alert-success" role="alert">Task added successfully!</div>';
                // Redirect after POST to prevent form resubmission and show message
                header("Location: " . BASE_PATH . "tasks.php?message=" . urlencode(strip_tags($task_action_message)));
                exit();
            } else {
                $task_action_message = '<div class="alert alert-danger" role="alert">Error adding task: ' . htmlspecialchars($stmt->error) . '</div>';
            }
            $stmt->close();
        } else {
            $task_action_message = '<div class="alert alert-danger" role="alert">Error preparing statement: ' . htmlspecialchars($conn->error) . '</div>';
        }
    }
}

// Fetch tasks for the current user (READ operation) - This logic remains the same
$tasks = [];
$task_list_error = '';
$stmt_select = $conn->prepare("SELECT id, title, description, status, DATE_FORMAT(due_date, '%Y-%m-%d') AS due_date_formatted, DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') AS created_at_formatted FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
if ($stmt_select) {
    $stmt_select->bind_param("i", $user_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    $stmt_select->close();
} else {
    $task_list_error = "Error fetching tasks: " . htmlspecialchars($conn->error);
}
?>
<!DOCTYPE html>
<html lang="uk">
<?php include 'parts/header.php'; ?>
<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>

    <div class="cd-hero">
        <?php include 'parts/navigation.php'; ?>

        <div class="container-fluid tm-page-pad">
            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                    <div class="tm-bg-white-translucent text-xs-left tm-textbox tm-textbox-padding">
                        <h2 class="tm-text-title">My To-Do List</h2>

                        <?php echo $task_action_message; // Display messages from task actions (add, update, delete) ?>

                        <h3 class="tm-text-title" style="margin-top:20px; margin-bottom:10px;">Add New Task</h3>
                        <form method="POST" action="tasks.php" class="tm-contact-form" style="margin-bottom: 30px;">
                            
                            <div class="form-group">
                                <label for="task_title">Title <span style="color:red;">*</span></label>
                                <input type="text" id="task_title" name="task_title" class="form-control" placeholder="Enter task title" required>
                            </div>
                            <div class="form-group">
                                <label for="task_description">Description (Optional)</label>
                                <textarea id="task_description" name="task_description" class="form-control" rows="3" placeholder="Enter task description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="task_due_date">Due Date (Optional)</label>
                                <input type="date" id="task_due_date" name="task_due_date" class="form-control">
                            </div>
                            <button type="submit" name="add_task" class="btn btn-primary tm-submit-btn">Add Task</button>
                        </form>

                        <hr>

                        <h3 class="tm-text-title" style="margin-top:30px; margin-bottom:20px;">Current Tasks</h3>
                        <?php if (!empty($task_list_error)): ?>
                            <div class="alert alert-danger" role="alert"><?php echo $task_list_error; ?></div>
                        <?php elseif (empty($tasks)): ?>
                            <p class="tm-text">You have no tasks yet. Add one above!</p>
                        <?php else: ?>
                            <ul class="list-group">
                                <?php foreach ($tasks as $task): ?>
                                    <li class="list-group-item <?php echo ($task['status'] == 'completed' ? 'list-group-item-success' : ''); ?>" style="margin-bottom: 10px; border-radius: .25rem;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <h5 class="mb-1 list-group-item-heading" style="<?php echo ($task['status'] == 'completed' ? 'text-decoration: line-through; color: #6c757d;' : ''); ?>">
                                                <?php echo htmlspecialchars($task['title']); ?>
                                            </h5>
                                            <span class="badge" style="background-color: <?php echo ($task['status'] == 'completed' ? '#28a745' : '#6c757d'); ?>; color: white; font-size: 0.8em; padding: 0.4em 0.6em;"><?php echo htmlspecialchars(ucfirst($task['status'])); ?></span>
                                        </div>

                                        <?php if (!empty($task['description'])): ?>
                                            <p class="mb-1 tm-text" style="font-size: 0.9em; color: #555; margin-top: 5px; margin-bottom: 5px;">
                                                <?php echo nl2br(htmlspecialchars($task['description'])); ?>
                                            </p>
                                        <?php endif; ?>
                                        <small class="text-muted">
                                            Created: <?php echo $task['created_at_formatted']; ?>
                                            <?php if (!empty($task['due_date_formatted'])): ?>
                                                | Due: <?php echo $task['due_date_formatted']; ?>
                                            <?php endif; ?>
                                        </small>
                                        <div style="margin-top: 10px;">
                                            
                                            <a href="<?php echo BASE_PATH; ?>edit_task.php?task_id=<?php echo $task['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                            
                                            
                                            <a href="<?php echo BASE_PATH; ?>tasks.php?action=toggle_status&task_id=<?php echo $task['id']; ?>" 
                                               class="btn btn-sm <?php echo ($task['status'] == 'completed' ? 'btn-outline-warning' : 'btn-outline-success'); ?>">
                                                <?php echo ($task['status'] == 'completed' ? 'Mark Pending' : 'Mark Completed'); ?>
                                            </a>
                                            
                                            
                                            <a href="<?php echo BASE_PATH; ?>tasks.php?action=delete_task&task_id=<?php echo $task['id']; ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirm('Are you sure you want to delete this task: \'<?php echo htmlspecialchars(addslashes($task['title'])); ?>\'?');">Delete</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'parts/footer.php'; ?>
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