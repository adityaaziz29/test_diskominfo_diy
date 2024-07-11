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
        <p>Data Fee mentor:</p>
        <table class="table table-bordered">
            <thead>
                <tr>
             
                    <th>Mentor</th>
                    <th>Jumlah Peserta</th>
                    <th>Total Fee</th>
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
            $.ajax({
                url: 'http://localhost/diskominfo/api/api.php/gaji_mentor',
                method: 'GET',
                success: function(data) {
                    var html = '';
                    console.log(data);
                    data.forEach(function(item) {
                        html += '<tr>';
                        html += '<td>' + item.mentor + '</td>';
                        html += '<td>' + item.jumlah_peserta + '</td>';
                        html += '<td>' + item.total_fee + '</td>';
                        html += '</tr>';
                    });
                    $('#data-table').html(html);
                },
                error: function(error) {
                    console.log('Error fetching data', error);
                }
            });
        });
    </script>
</body>
</html>
