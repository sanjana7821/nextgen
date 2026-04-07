<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<?php include("../includes/navbar.php"); ?>

<div class="container mt-5">
    <h2 class="mb-4">Notifications</h2>
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Type</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $notifs = $conn->query("SELECT * FROM notifications WHERE student_id='$student_id' OR student_id IS NULL ORDER BY created_at DESC");
                    if ($notifs->num_rows > 0) {
                        while ($notif = $notifs->fetch_assoc()) {
                            $badge = 'secondary';
                            if ($notif['type'] == 'status_update') $badge = 'info';
                            elseif ($notif['type'] == 'interview') $badge = 'warning';
                            elseif ($notif['type'] == 'general') $badge = 'primary';
                            echo "<tr>
                                <td><span class='badge bg-{$badge}'>".ucfirst($notif['type'])."</span></td>
                                <td>".$notif['message']."</td>
                                <td>".date('d M Y H:i', strtotime($notif['created_at']))."</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>No notifications yet</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
</body>
</html>