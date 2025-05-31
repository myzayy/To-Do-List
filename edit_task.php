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
$task_action_message = '';    // For success/error messages
$task_id = null;
$task = null; // Variable to hold task details

// Get task_id from URL
if (isset($_GET['task_id'])) {
    $task_id = intval($_GET['task_id']);
} else {
    // If no task_id is provided in URL, redirect or show error
    $_SESSION['task_action_error_message'] = 'No task ID provided.'; // Use session for message after redirect
    header("Location: " . BASE_PATH . "tasks.php");
    exit();
}

// Handle task update submission (UPDATE operation)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_task'])) {
    $submitted_task_id = isset($_POST['task_id']) ? intval($_POST['task_id']) : null;
    $title = trim($_POST['task_title']);
    $description = isset($_POST['task_description']) ? trim($_POST['task_description']) : null;
    $due_date = !empty($_POST['task_due_date']) ? trim($_POST['task_due_date']) : null;
    // Status is NOT updated from this form anymore

    if ($submitted_task_id !== $task_id) {
        // Basic check against task_id tampering
        $task_action_message = '<div class="alert alert-danger" role="alert">Task ID mismatch.</div>';
    } elseif (empty($title)) {
        $task_action_message = '<div class="alert alert-danger" role="alert">Task title cannot be empty.</div>';
    } else {
        // Prepare statement to update task (title, description, due_date only)
        // Ensure user_id check to prevent unauthorized edits
        $stmt_update = $conn->prepare("UPDATE tasks SET title = ?, description = ?, due_date = ? WHERE id = ? AND user_id = ?");
        if ($stmt_update) {
            // Bind parameters: sssii (title, description, due_date are strings; id, user_id are integers)
            $stmt_update->bind_param("sssii", $title, $description, $due_date, $task_id, $user_id);
            if ($stmt_update->execute()) {
                if ($stmt_update->affected_rows > 0) {
                    $_SESSION['task_action_success_message'] = 'Task updated successfully!';
                } else {
                    $_SESSION['task_action_info_message'] = 'No changes were made to the task (perhaps the data was the same).';
                }
                header("Location: " . BASE_PATH . "tasks.php"); // Redirect to tasks list
                exit();
            } else {
                $task_action_message = '<div class="alert alert-danger" role="alert">Error updating task: ' . htmlspecialchars($stmt_update->error) . '</div>';
            }
            $stmt_update->close();
        } else {
            $task_action_message = '<div class="alert alert-danger" role="alert">Error preparing update statement: ' . htmlspecialchars($conn->error) . '</div>';
        }
    }
}

// Fetch the task details to pre-fill the form (if not a POST request or if POST failed)
if ($task_id) {
    // We don't necessarily need to fetch 'status' here for the form if we are not editing it,
    // but it's good to keep it for consistency or if we display it.
    $stmt_fetch = $conn->prepare("SELECT id, title, description, status, DATE_FORMAT(due_date, '%Y-%m-%d') AS due_date_formatted FROM tasks WHERE id = ? AND user_id = ?");
    if ($stmt_fetch) {
        $stmt_fetch->bind_param("ii", $task_id, $user_id);
        $stmt_fetch->execute();
        $result = $stmt_fetch->get_result();
        if ($result->num_rows === 1) {
            $task = $result->fetch_assoc();
        } else {
            // Task not found or does not belong to the user
            $_SESSION['task_action_error_message'] = 'Task not found or you do not have permission to edit it.';
            header("Location: " . BASE_PATH . "tasks.php");
            exit();
        }
        $stmt_fetch->close();
    } else {
        $task_action_message = '<div class="alert alert-danger" role="alert">Error fetching task details: ' . htmlspecialchars($conn->error) . '</div>';
        $task = null; // Ensure task is null so form doesn't try to display
    }
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
                <div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <div class="tm-bg-white-translucent text-xs-left tm-textbox tm-textbox-padding">
                        <h2 class="tm-text-title">Edit Task</h2>

                        <?php echo $task_action_message; // Display messages from update attempt ?>
                        
                        <?php if ($task): // Only show form if task was successfully fetched ?>
                        <form method="POST" action="<?php echo BASE_PATH; ?>edit_task.php?task_id=<?php echo $task['id']; ?>" class="tm-contact-form">
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                            
                            <div class="form-group">
                                <label for="task_title">Title <span style="color:red;">*</span></label>
                                <input type="text" id="task_title" name="task_title" class="form-control" placeholder="Enter task title" 
                                       value="<?php echo htmlspecialchars($task['title']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="task_description">Description (Optional)</label>
                                <textarea id="task_description" name="task_description" class="form-control" rows="5" 
                                          placeholder="Enter task description"><?php echo htmlspecialchars($task['description']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="task_due_date">Due Date (Optional)</label>
                                <input type="date" id="task_due_date" name="task_due_date" class="form-control"
                                       value="<?php echo htmlspecialchars($task['due_date_formatted']); ?>">
                            </div>
                                                                                    
                            <button type="submit" name="update_task" class="btn btn-primary tm-submit-btn">Update Task</button>
                            <a href="<?php echo BASE_PATH; ?>tasks.php" class="btn btn-secondary tm-submit-btn" style="background-color: #6c757d; margin-left: 10px;">Cancel</a>
                        </form>
                        <?php else: ?>
                            <?php if (empty($task_action_message)): // Show this only if no other error message is already set ?>
                                <p class="tm-text">Could not load task details for editing.</p>
                            <?php endif; ?>
                             <a href="<?php echo BASE_PATH; ?>tasks.php" class="btn btn-primary tm-submit-btn">Back to Tasks</a>
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