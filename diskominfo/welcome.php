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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <div class="row">
            <div class="col-md-6">
                <h3>Grafik Gaji Mentor</h3>
                <canvas id="gajiMentorChart"></canvas>
            </div>
            <div class="col-md-6">
                <h3>Grafik Jumlah Peserta Pelatihan</h3>
                <canvas id="jumlahUserPelatihanChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies for responsive navbar -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            // Fetch data for gaji mentor chart
            $.ajax({
                url: 'http://localhost/diskominfo/api/api.php/gaji_mentor',
                method: 'GET',
                success: function(data) {
                    const mentorNames = data.map(item => item.mentor);
                    const totalFees = data.map(item => item.total_fee);

                    const ctx = document.getElementById('gajiMentorChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: mentorNames,
                            datasets: [{
                                label: 'Total Fee',
                                data: totalFees,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                },
                error: function(error) {
                    console.log('Error fetching gaji mentor data', error);
                }
            });

            // Fetch data for jumlah user pelatihan chart
            $.ajax({
                url: 'http://localhost/diskominfo/api/api.php/jumlah_user_pelatihan',
                method: 'GET',
                success: function(data) {
                    const mentorNames = data.map(item => item.mentor);
                    const jumlahPeserta = data.map(item => item.jumlah_peserta);

                    const ctx = document.getElementById('jumlahUserPelatihanChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: mentorNames,
                            datasets: [{
                                label: 'Jumlah Peserta',
                                data: jumlahPeserta,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                },
                error: function(error) {
                    console.log('Error fetching jumlah user pelatihan data', error);
                }
            });
        });
    </script>
</body>
</html>
