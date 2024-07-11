<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Welcome, <?php echo $_SESSION['username']; ?>!</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="data_user.php">Data User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_courses.php">Data Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_course_user.php">Data Courses User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_user_course.php">Data User Course</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gelar_sarjana.php">Gelar Sarjana</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="selain_sarjana.php">Selain Sarjana</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="fee_mentor.php">Fee Mentor</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <p>Data User :</p>
        <button class="btn btn-primary mb-3" id="addUserBtn">Tambah User</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="data-table">
                <!-- Data akan diisi oleh JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and dependencies for responsive navbar -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            fetchUsers();

            $('#addUserBtn').click(function() {
                window.location.href = 'edit_user.php';
            });

            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                deleteUser(id);
            });
        });

        function fetchUsers() {
            $.ajax({
                url: 'http://localhost/diskominfo/api/api.php/data_user',
                method: 'GET',
                success: function(data) {
                    let html = '';
                    data.forEach(function(item) {
                        html += '<tr>';
                        html += '<td>' + item.username + '</td>';
                        html += '<td>' + item.email + '</td>';
                        html += '<td>';
                        html += '<a href="edit_user.php?id=' + item.id + '" class="btn btn-sm btn-warning">Edit</a> ';
                        html += '<button class="btn btn-sm btn-danger delete-btn" data-id="' + item.id + '">Delete</button>';
                        html += '</td>';
                        html += '</tr>';
                    });
                    $('#data-table').html(html);
                },
                error: function(error) {
                    console.log('Error fetching data', error);
                }
            });
        }

        function deleteUser(id) {
            $.ajax({
                url: 'http://localhost/diskominfo/api/api.php/user',
                method: 'DELETE',
                contentType: 'application/json',
                data: JSON.stringify({ id: id }),
                success: function(data) {
                    fetchUsers();
                },
                error: function(error) {
                    console.log('Error deleting user', error);
                }
            });
        }
    </script>
</body>
</html>
