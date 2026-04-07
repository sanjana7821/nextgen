<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['admin_id'])) { header("Location: ../login.php"); exit(); }
$id = $_GET['id'] ?? 0;

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $required_skills = $_POST['required_skills'];
    $cgpa_requirement = $_POST['cgpa_requirement'];
    $stmt = $conn->prepare("UPDATE jobs SET title=?, description=?, required_skills=?, cgpa_requirement=? WHERE job_id=?");
    $stmt->bind_param("ssssi", $title, $description, $required_skills, $cgpa_requirement, $id);
    if($stmt->execute()) { header("Location: manage_jobs.php?msg=updated"); exit(); }
}

$stmt = $conn->prepare("SELECT * FROM jobs WHERE job_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$j = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job — NextGen</title>
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
            <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;">Edit Job</h2>
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; padding:1.5rem; max-width:800px; margin-top:1.5rem;">
                <form method="POST">
                    <div class="mb-3"><label class="form-label" style="font-weight:600; font-size:0.85rem;">Job Title</label><input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($j['title']); ?>" required></div>
                    <div class="mb-3"><label class="form-label" style="font-weight:600; font-size:0.85rem;">Description</label><textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($j['description']); ?></textarea></div>
                    <div class="mb-3"><label class="form-label" style="font-weight:600; font-size:0.85rem;">Required Skills</label><input type="text" name="required_skills" class="form-control" value="<?php echo htmlspecialchars($j['required_skills']); ?>" required></div>
                    <div class="mb-4"><label class="form-label" style="font-weight:600; font-size:0.85rem;">CGPA Requirement</label><input type="number" step="0.01" name="cgpa_requirement" class="form-control" value="<?php echo htmlspecialchars($j['cgpa_requirement']); ?>"></div>
                    <button type="submit" name="update" class="btn btn-primary w-100" style="background:#6366f1; border:none; border-radius:9999px;">Save Changes</button>
                    <a href="manage_jobs.php" class="btn btn-light w-100 mt-2" style="border-radius:9999px;">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include("../includes/footer.php"); ?>
</body></html>
