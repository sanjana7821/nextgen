<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['company_id'])) { header("Location: ../login.php"); exit(); }
$company_id = $_SESSION['company_id'];
$postMsg = '';

if (isset($_POST['post_job'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $skills = $_POST['skills'];
    $cgpa = $_POST['cgpa'];
    $sql = "INSERT INTO jobs (company_id, title, description, required_skills, cgpa_requirement) VALUES ('$company_id', '$title', '$description', '$skills', '$cgpa')";
    if ($conn->query($sql) === TRUE) {
        $postMsg = 'success';
    } else {
        $postMsg = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job — NextGen</title>
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
            <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;">Post a New Job</h2>
            <p style="color:#64748b; font-size:0.9rem; margin-bottom:1.5rem;">Create a new job posting for students to apply</p>

            <?php if ($postMsg === 'success'): ?>
                <div class="alert alert-success d-flex align-items-center gap-2" style="border-radius:12px; font-size:0.9rem;"><i class="fas fa-check-circle"></i> Job posted successfully! <a href="company_dashboard.php" style="color:#059669; font-weight:600;">View Dashboard</a></div>
            <?php elseif ($postMsg === 'error'): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2" style="border-radius:12px; font-size:0.9rem;"><i class="fas fa-exclamation-circle"></i> Failed to post job.</div>
            <?php endif; ?>

            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                    <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-plus-circle me-2" style="color:#6366f1;"></i>Job Details</h4>
                </div>
                <div style="padding:1.5rem;">
                    <form method="POST">
                        <div class="mb-3">
                            <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.4rem;">Job Title</label>
                            <input type="text" name="title" class="form-control" required placeholder="e.g. Frontend Developer" style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.7rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.4rem;">Description</label>
                            <textarea name="description" class="form-control" rows="3" required placeholder="Describe the role and responsibilities" style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.7rem 1rem;"></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.4rem;">Required Skills <small style="color:#94a3b8;">(comma separated)</small></label>
                                <textarea name="skills" class="form-control" rows="2" required placeholder="e.g. JavaScript, React, Node.js" style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.7rem 1rem;"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.4rem;">Minimum CGPA</label>
                                <input type="number" step="0.01" name="cgpa" class="form-control" required placeholder="e.g. 7.0" style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.7rem 1rem;">
                            </div>
                        </div>
                        <button type="submit" name="post_job" class="btn btn-primary w-100 mt-4" style="background:linear-gradient(135deg,#059669,#10b981,#34d399); border:none; border-radius:9999px; padding:0.75rem; font-weight:600;">
                            <i class="fas fa-paper-plane me-2"></i>Post Job
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("../includes/footer.php"); ?>
</body>
</html>