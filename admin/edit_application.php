<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['admin_id'])) { header("Location: ../login.php"); exit(); }
$id = $_GET['id'] ?? 0;

if (isset($_POST['update'])) {
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE applications SET status=? WHERE application_id=?");
    $stmt->bind_param("si", $status, $id);
    if($stmt->execute()) { header("Location: manage_applications.php?msg=updated"); exit(); }
}

$stmt = $conn->prepare("SELECT applications.*, students.name AS student_name, jobs.title FROM applications JOIN students ON applications.student_id = students.student_id JOIN jobs ON applications.job_id = jobs.job_id WHERE application_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$a = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Application — NextGen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; background: #f8fafc; }</style>
</head>
<body>
<?php include("../includes/navbar.php"); ?>
<div class="container-fluid" style="max-width: 1400px; margin: 0 auto; padding: 1.5rem;">
    <div class="row g-4">
        <div class="col-lg-3"><?php include("sidebar.php"); ?></div>
        <div class="col-lg-9">
            <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;">Edit Application Status</h2>
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; padding:1.5rem; max-width:600px; margin-top:1.5rem;">
                <p><strong>Student:</strong> <?php echo htmlspecialchars($a['student_name']); ?></p>
                <p><strong>Job:</strong> <?php echo htmlspecialchars($a['title']); ?></p>
                
                <form method="POST" class="mt-4">
                    <div class="mb-4">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Status</label>
                        <select name="status" class="form-select">
                            <option value="Applied" <?php if($a['status']=='Applied') echo 'selected'; ?>>Applied</option>
                            <option value="Shortlisted" <?php if($a['status']=='Shortlisted') echo 'selected'; ?>>Shortlisted</option>
                            <option value="Selected" <?php if($a['status']=='Selected') echo 'selected'; ?>>Selected</option>
                            <option value="Rejected" <?php if($a['status']=='Rejected') echo 'selected'; ?>>Rejected</option>
                        </select>
                    </div>
                    <button type="submit" name="update" class="btn btn-primary w-100" style="background:#6366f1; border:none; border-radius:9999px;">Update Status</button>
                    <a href="manage_applications.php" class="btn btn-light w-100 mt-2" style="border-radius:9999px;">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include("../includes/footer.php"); ?>
</body></html>
