<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$course = null;

if ($id) {
    $query = "SELECT * FROM courses WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();
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
        <h2><?php echo $id ? 'Edit' : 'Tambah'; ?> Course</h2>
        <form id="courseForm">
            <div class="form-group">
                <label for="course">Course</label>
                <input type="text" class="form-control" id="course" value="<?php echo $course ? $course['course'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="mentor">Mentor</label>
                <input type="text" class="form-control" id="mentor" value="<?php echo $course ? $course['mentor'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" value="<?php echo $course ? $course['title'] : ''; ?>" required>
            </div>
            <input type="hidden" id="courseId" value="<?php echo $course ? $course['id'] : ''; ?>">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $('#courseForm').on('submit', function(event) {
            event.preventDefault();
            const id = $('#courseId').val();
            if (id) {
                updateCourse(id);
            } else {
                createCourse();
            }
        });

        function createCourse() {
            const course = {
                course: $('#course').val(),
                mentor: $('#mentor').val(),
                title: $('#title').val()
            };

            $.ajax({
                url: 'http://localhost/diskominfo/api/api.php/course', // Ensure this matches your API endpoint
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(course),
                success: function(data) {
                    window.location.href = 'data_courses.php';
                },
                error: function(error) {
                    console.log('Error creating course', error);
                }
            });
        }

        function updateCourse(id) {
            const course = {
                id: id,
                course: $('#course').val(),
                mentor: $('#mentor').val(),
                title: $('#title').val()
            };

            $.ajax({
                url: 'http://localhost/diskominfo/api/api.php/course',
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(course),
                success: function(data) {
                    window.location.href = 'data_courses.php';
                },
                error: function(error) {
                    console.log('Error updating user', error);
                }
            });
        }
    </script>
</body>

</html>