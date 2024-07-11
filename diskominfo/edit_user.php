<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$user = null;

if ($id) {
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Edit' : 'Tambah'; ?> User</title>
    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2><?php echo $id ? 'Edit' : 'Tambah'; ?> User</h2>
        <form id="userForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" value="<?php echo $user ? $user['username'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" value="<?php echo $user ? $user['email'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" value="<?php echo $user ? $user['email'] : ''; ?>" required>
            </div>
            <input type="hidden" id="userId" value="<?php echo $user ? $user['id'] : ''; ?>">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $('#userForm').on('submit', function(event) {
            event.preventDefault();
            const id = $('#userId').val();
            if (id) {
                updateUser(id);
            } else {
                createUser();
            }
        });

        function createUser() {
            const user = {
                username: $('#username').val(),
                email: $('#email').val(),
                password: $('#password').val()
            };

            $.ajax({
                url: 'http://localhost/diskominfo/api/api.php/user',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(user),
                success: function(data) {
                    window.location.href = 'data_user.php';
                },
                error: function(error) {
                    console.log('Error creating user', error);
                }
            });
        }

        function updateUser(id) {
            const user = {
                id: id,
                username: $('#username').val(),
                email: $('#email').val(),
                password: $('#password').val()
            };

            $.ajax({
                url: 'http://localhost/diskominfo/api/api.php/user',
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(user),
                success: function(data) {
                    window.location.href = 'data_user.php';
                },
                error: function(error) {
                    console.log('Error updating user', error);
                }
            });
        }
    </script>
</body>
</html>
