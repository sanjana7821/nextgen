<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['student_id'])) { header("Location: ../login.php"); exit(); }
$student_id = $_SESSION['student_id'];
$job_id = $_GET['id'] ?? 0;

if (isset($_POST['apply'])) {
    $stmt = $conn->prepare("SELECT * FROM applications WHERE student_id=? AND job_id=?");
    $stmt->bind_param("ii", $student_id, $job_id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        header("Location: view_jobs.php?msg=already_applied");
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO applications (student_id, job_id, status) VALUES (?, ?, 'Applied')");
        $stmt->bind_param("ii", $student_id, $job_id);
        $stmt->execute();
        header("Location: view_jobs.php?msg=applied");
    }
    $stmt->close();
    exit();
}

$stmt = $conn->prepare("SELECT jobs.*, companies.name AS company_name FROM jobs JOIN companies ON jobs.company_id = companies.company_id WHERE job_id=?");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$job) { header("Location: view_jobs.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Application — NextGen</title>
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
            <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;">Confirm Application</h2>
            <p style="color:#64748b; font-size:0.9rem; margin-bottom:1.5rem;">Review the job details before submitting your application</p>
            
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden; max-width:600px;">
                <div style="padding:1.5rem; border-bottom:1px solid #f1f5f9; background:linear-gradient(135deg, rgba(99,102,241,0.05), rgba(139,92,246,0.05));">
                    <h3 style="font-size:1.25rem; font-weight:700; margin-bottom:0.25rem; color:#1e293b;"><?php echo htmlspecialchars($job['title']); ?></h3>
                    <div style="color:#6366f1; font-weight:600; font-size:0.9rem;"><i class="fas fa-building me-1"></i><?php echo htmlspecialchars($job['company_name']); ?></div>
                </div>
                <div style="padding:1.5rem;">
                    <div class="mb-4">
                        <h6 style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; color:#94a3b8; font-weight:700; margin-bottom:0.5rem;">Description</h6>
                        <p style="color:#475569; font-size:0.9rem; line-height:1.5;"><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <h6 style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; color:#94a3b8; font-weight:700; margin-bottom:0.25rem;">Required Skills</h6>
                            <div style="color:#334155; font-size:0.9rem; font-weight:500;"><?php echo htmlspecialchars($job['required_skills']); ?></div>
                        </div>
                        <div class="col-6">
                            <h6 style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; color:#94a3b8; font-weight:700; margin-bottom:0.25rem;">CGPA Requirement</h6>
                            <div style="color:#334155; font-size:0.9rem; font-weight:500;"><?php echo htmlspecialchars($job['cgpa_requirement']); ?>+</div>
                        </div>
                    </div>
                    
                    <form method="POST">
                        <button type="submit" name="apply" class="btn btn-primary w-100" style="background:linear-gradient(135deg,#6366f1,#8b5cf6); border:none; border-radius:9999px; padding:0.75rem; font-weight:600;">
                            Submit Application
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("../includes/footer.php"); ?>
</body></html>