<?php
require_once '../config/config.php'; // Defines BASE_PATH
session_start();
require_once '../config/connect.php'; // Database connection

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_PATH . "index.php");
    exit();
}

$user_to_edit_id = null;
$user_to_edit = null; // Variable to hold user details
$action_message = ''; // For displaying messages on this page

// Get user_id from URL parameter
if (isset($_GET['user_id'])) {
    $user_to_edit_id = intval($_GET['user_id']);
} else {
    // Redirect if no user_id is provided
    $_SESSION['user_action_error_message'] = 'No user ID provided.';
    header("Location: " . BASE_PATH . "admin/manage_users.php");
    exit();
}

// Prevent admins from editing their own role on this page to avoid self-lockout
if ($user_to_edit_id === $_SESSION['user_id']) {
    $_SESSION['user_action_info_message'] = 'You cannot edit your own role through this form.';
    header("Location: " . BASE_PATH . "admin/manage_users.php");
    exit();
}

// Handle form submission to update the user's role
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user_role'])) {
    $new_role = trim($_POST['user_role']);
    $submitted_user_id = intval($_POST['user_id']);

    // Validate the new role
    $valid_roles = ['user', 'admin'];
    if (!in_array($new_role, $valid_roles)) {
        $action_message = '<div class="alert alert-danger" role="alert">Invalid role selected.</div>';
    } elseif ($submitted_user_id !== $user_to_edit_id) {
        // Security check
        $action_message = '<div class="alert alert-danger" role="alert">User ID mismatch.</div>';
    } else {
        // Prepare and execute the update statement
        $stmt_update = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        if ($stmt_update) {
            $stmt_update->bind_param("si", $new_role, $user_to_edit_id);
            if ($stmt_update->execute()) {
                $_SESSION['user_action_success_message'] = "User role updated successfully.";
                header("Location: " . BASE_PATH . "admin/manage_users.php"); // Redirect back to the user list
                exit();
            } else {
                $action_message = '<div class="alert alert-danger" role="alert">Error updating user role: ' . htmlspecialchars($stmt_update->error) . '</div>';
            }
            $stmt_update->close();
        } else {
            $action_message = '<div class="alert alert-danger" role="alert">Error preparing update statement: ' . htmlspecialchars($conn->error) . '</div>';
        }
    }
}

// Fetch the user's details to pre-fill the form
if ($user_to_edit_id) {
    $stmt_fetch = $conn->prepare("SELECT id, username, role FROM users WHERE id = ?");
    if ($stmt_fetch) {
        $stmt_fetch->bind_param("i", $user_to_edit_id);
        $stmt_fetch->execute();
        $result = $stmt_fetch->get_result();
        if ($result->num_rows === 1) {
            $user_to_edit = $result->fetch_assoc();
        } else {
            // User not found
            $_SESSION['user_action_error_message'] = 'User not found.';
            header("Location: " . BASE_PATH . "admin/manage_users.php");
            exit();
        }
        $stmt_fetch->close();
    } else {
        $action_message = '<div class="alert alert-danger" role="alert">Error fetching user details: ' . htmlspecialchars($conn->error) . '</div>';
        $user_to_edit = null; // Ensure form is not displayed on error
    }
}
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
                <div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <div class="tm-bg-white-translucent text-xs-left tm-textbox tm-textbox-padding">
                        <h2 class="tm-text-title">Edit User Role</h2>
                        
                        <?php echo $action_message; // Display messages from update attempt ?>
                        
                        <?php if ($user_to_edit): // Only show form if user data was successfully fetched ?>
                            <p class="tm-text">You are editing the role for user: <strong><?php echo htmlspecialchars($user_to_edit['username']); ?></strong></p>

                            <form method="POST" action="<?php echo BASE_PATH; ?>admin/edit_user.php?user_id=<?php echo $user_to_edit['id']; ?>" class="tm-contact-form">
                                <input type="hidden" name="user_id" value="<?php echo $user_to_edit['id']; ?>">
                                
                                <div class="form-group">
                                    <label for="user_role">Role</label>
                                    <select id="user_role" name="user_role" class="form-control">
                                        <option value="user" <?php echo ($user_to_edit['role'] == 'user' ? 'selected' : ''); ?>>User</option>
                                        <option value="admin" <?php echo ($user_to_edit['role'] == 'admin' ? 'selected' : ''); ?>>Admin</option>
                                    </select>
                                </div>
                                
                                <button type="submit" name="update_user_role" class="btn btn-primary tm-submit-btn">Update Role</button>
                                <a href="<?php echo BASE_PATH; ?>admin/manage_users.php" class="btn btn-secondary tm-submit-btn" style="background-color: #6c757d; margin-left: 10px;">Cancel</a>
                            </form>
                        <?php else: ?>
                            <p class="tm-text">Could not load user details for editing.</p>
                            <a href="<?php echo BASE_PATH; ?>admin/manage_users.php" class="btn btn-primary tm-submit-btn">Back to User List</a>
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