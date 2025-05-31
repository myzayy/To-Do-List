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

// Handle new task submission (CREATE operation)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $title = trim($_POST['task_title']);
    // Ensure description and due_date are set, even if empty, to avoid undefined index notices
    $description = isset($_POST['task_description']) ? trim($_POST['task_description']) : null;
    $due_date = !empty($_POST['task_due_date']) ? trim($_POST['task_due_date']) : null;

    if (empty($title)) {
        $task_action_message = '<div class="alert alert-danger" role="alert">Task title cannot be empty.</div>';
    } else {
        // Prepare statement to insert new task
        $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, due_date, status) VALUES (?, ?, ?, ?, 'pending')");
        if ($stmt) {
            $stmt->bind_param("isss", $user_id, $title, $description, $due_date); // "i" for user_id, "s" for title, description, due_date
            if ($stmt->execute()) {
                $task_action_message = '<div class="alert alert-success" role="alert">Task added successfully!</div>';
            } else {
                // Error during execution
                $task_action_message = '<div class="alert alert-danger" role="alert">Error adding task: ' . htmlspecialchars($stmt->error) . '</div>';
            }
            $stmt->close();
        } else {
            // Error during prepare
            $task_action_message = '<div class="alert alert-danger" role="alert">Error preparing statement: ' . htmlspecialchars($conn->error) . '</div>';
        }
    }
}

// Fetch tasks for the current user (READ operation)
$tasks = []; // Initialize empty array for tasks
$task_list_error = ''; // Initialize error message for task list

// Prepare statement to select tasks
$stmt_select = $conn->prepare("SELECT id, title, description, status, DATE_FORMAT(due_date, '%Y-%m-%d') AS due_date_formatted, DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') AS created_at_formatted FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
if ($stmt_select) {
    $stmt_select->bind_param("i", $user_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row; // Add each task to the tasks array
    }
    $stmt_select->close();
} else {
    // Handle error if statement preparation fails
    $task_list_error = "Error fetching tasks: " . htmlspecialchars($conn->error);
}

// $conn->close(); // Connection will be closed automatically at the end of the script
?>
<!DOCTYPE html>
<html lang="uk">
<?php include 'parts/header.php'; // Include common header (links CSS, etc.) ?>
<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>

    <div class="cd-hero"> 
        <?php include 'parts/navigation.php'; // Include common navigation ?>

        
        <div class="container-fluid tm-page-pad">
            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                    <div class="tm-bg-white-translucent text-xs-left tm-textbox tm-textbox-padding"> 
                        <h2 class="tm-text-title">My To-Do List</h2>

                        <?php echo $task_action_message; // Display messages from task creation ?>

                        
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
                                    <li class="list-group-item" style="margin-bottom: 10px; border-radius: .25rem; <?php echo ($task['status'] == 'completed' ? 'background-color: #dff0d8;' : ''); ?>">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1 list-group-item-heading" style="<?php echo ($task['status'] == 'completed' ? 'text-decoration: line-through;' : ''); ?>">
                                                <?php echo htmlspecialchars($task['title']); ?>
                                            </h5>
                                            <span class="badge" style="background-color: <?php echo ($task['status'] == 'completed' ? '#28a745' : '#6c757d'); ?>; color: white; margin-left: 10px; font-size: 0.8em; padding: 0.4em 0.6em; vertical-align: middle;"><?php echo htmlspecialchars(ucfirst($task['status'])); ?></span>
                                        </div>

                                        <?php if (!empty($task['description'])): ?>
                                            <p class="mb-1 tm-text" style="font-size: 0.9em; color: #555;">
                                                <?php echo nl2br(htmlspecialchars($task['description'])); // nl2br to respect line breaks ?>
                                            </p>
                                        <?php endif; ?>
                                        <small class="text-muted">
                                            Created: <?php echo $task['created_at_formatted']; ?>
                                            <?php if (!empty($task['due_date_formatted'])): ?>
                                                | Due: <?php echo $task['due_date_formatted']; ?>
                                            <?php endif; ?>
                                        </small>
                                        <div style="margin-top: 10px;">
                                            
                                            <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                                            <a href="#" class="btn btn-sm <?php echo ($task['status'] == 'completed' ? 'btn-outline-warning' : 'btn-outline-success'); ?>">
                                                <?php echo ($task['status'] == 'completed' ? 'Mark Pending' : 'Mark Completed'); ?>
                                            </a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        

        <?php include 'parts/footer.php'; // Include common footer ?>
    </div>

    <script src="<?php echo BASE_PATH; ?>js/jquery-1.11.3.min.js"></script>
    <script src="https://www.atlasestateagents.co.uk/javascript/tether.min.js"></script>
    <script src="<?php echo BASE_PATH; ?>js/bootstrap.min.js"></script>
    <script src="<?php echo BASE_PATH; ?>js/jquery.magnific-popup.min.js"></script>
    <script>
        $(window).on('load', function(){ // Changed from $(window).load() to $(window).on('load', ...)
            $('body').addClass('loaded'); // Preloader hide

            // Mobile menu collapse handler
            $('#tmNavbar .nav-link').on('click', function(){
                if ($('.navbar-toggler').is(':visible') && $('#tmNavbar').hasClass('show')) {
                    $('#tmNavbar').collapse('hide');
                }
            });
        });
    </script>
</body>
</html>