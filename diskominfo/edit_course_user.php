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
    $query = "SELECT * FROM usercourse WHERE id = ?";
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
    <title><?php echo $id ? 'Edit' : 'Tambah'; ?> Course</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <h2><?php echo $id ? 'Edit' : 'Tambah'; ?> Course</h2>
        <form id="courseForm">
            <div class="form-group">
                <label for="user">User</label>
                <select class="form-control" id="user" required>
                    <option value="">Select User</option>
                </select>
            </div>
            <div class="form-group">
                <label for="course">Course</label>
                <select class="form-control" id="course" required>
                    <option value="">Select Course</option>
                </select>
            </div>
            <input type="hidden" id="courseId" value="<?php echo $course ? $course['id'] : ''; ?>">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch users and populate the user select element
            $.ajax({
                url: 'http://localhost/diskominfo/api/api.php/data_get_user',
                method: 'GET',
                success: function(data) {
                    var users = JSON.parse(data);
                    var userSelect = $('#user');

                    users.forEach(function(user) {
                        userSelect.append('<option value="' + user.id + '">' + user.username + '</option>');
                    });

                    <?php if ($course): ?>
                        $('#user').val('<?php echo $course['id_user']; ?>');
                    <?php endif; ?>
                },
                error: function(error) {
                    console.error('Error fetching users:', error);
                }
            });

            // Fetch courses and populate the course select element
            $.ajax({
                url: 'http://localhost/diskominfo/api/api.php/data_get_course',
                method: 'GET',
                success: function(data) {
                    var courses = JSON.parse(data);
                    var courseSelect = $('#course');

                    courses.forEach(function(course) {
                        courseSelect.append('<option value="' + course.id + '">' + course.course + '</option>');
                    });

                    <?php if ($course): ?>
                        $('#course').val('<?php echo $course['id_course']; ?>');
                    <?php endif; ?>
                },
                error: function(error) {
                    console.error('Error fetching courses:', error);
                }
            });
        });

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
            const courseData = {
                id_user: $('#user').val(),
                id_course: $('#course').val()
            };

            $.ajax({
                url: 'http://localhost/diskominfo/api/api.php/course', // Ensure this matches your API endpoint
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(courseData),
                success: function(data) {
                    window.location.href = 'data_courses.php';
                },
                error: function(error) {
                    console.error('Error creating course', error);
                }
            });
        }

        function updateCourse(id) {
            const courseData = {
                id: id,
                id_user: $('#user').val(),
                id_course: $('#course').val()
            };

            $.ajax({
                url: 'http://localhost/diskominfo/api/api.php/course',
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(courseData),
                success: function(data) {
                    window.location.href = 'data_courses.php';
                },
                error: function(error) {
                    console.error('Error updating course', error);
                }
            });
        }
    </script>
</body>

</html>
