<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['student_id'])) { header("Location: ../login.php"); exit(); }
$student_id = $_SESSION['student_id'];
$uploadMsg = '';

if (isset($_POST['upload_resume'])) {
    $target_dir = "../uploads/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
    $file_name = basename($_FILES["resume"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($file_type != "pdf" && $file_type != "doc" && $file_type != "docx") {
        $uploadMsg = 'invalid';
    } else {
        if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO resumes (student_id, file_path) VALUES ('$student_id', '$target_file')";
            if ($conn->query($sql) === TRUE) {
                $conn->query("UPDATE students SET resume='$target_file' WHERE student_id='$student_id'");
                $uploadMsg = 'success';
            } else {
                $uploadMsg = 'error';
            }
        } else {
            $uploadMsg = 'error';
        }
    }
}

$latestResume = $conn->query("SELECT file_path, uploaded_at FROM resumes WHERE student_id='$student_id' ORDER BY uploaded_at DESC LIMIT 1");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Resume — NextGen</title>
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
            <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;">Upload Resume</h2>
            <p style="color:#64748b; font-size:0.9rem; margin-bottom:1.5rem;">Upload your latest resume for job applications</p>

            <?php if ($uploadMsg === 'success'): ?>
                <div class="alert alert-success d-flex align-items-center gap-2" style="border-radius:12px; font-size:0.9rem;"><i class="fas fa-check-circle"></i> Resume uploaded successfully!</div>
            <?php elseif ($uploadMsg === 'invalid'): ?>
                <div class="alert alert-warning d-flex align-items-center gap-2" style="border-radius:12px; font-size:0.9rem;"><i class="fas fa-exclamation-triangle"></i> Only PDF, DOC, DOCX files are allowed.</div>
            <?php elseif ($uploadMsg === 'error'): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2" style="border-radius:12px; font-size:0.9rem;"><i class="fas fa-exclamation-circle"></i> Error uploading file. Please try again.</div>
            <?php endif; ?>

            <!-- Upload Card -->
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden; margin-bottom:1.5rem;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                    <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-cloud-upload-alt me-2" style="color:#6366f1;"></i>Upload New Resume</h4>
                </div>
                <div style="padding:1.5rem;">
                    <form method="POST" enctype="multipart/form-data">
                        <div style="border:2px dashed #c7d2fe; border-radius:12px; padding:2rem; text-align:center; margin-bottom:1rem; background:#eef2ff;">
                            <i class="fas fa-file-pdf" style="font-size:2.5rem; color:#6366f1; margin-bottom:0.75rem; display:block;"></i>
                            <p style="font-weight:600; color:#334155; margin-bottom:0.5rem;">Drag & drop or choose a file</p>
                            <p style="color:#94a3b8; font-size:0.85rem; margin-bottom:1rem;">PDF, DOC, DOCX — Max 10MB</p>
                            <input type="file" name="resume" class="form-control" required style="max-width:300px; margin:0 auto; border:1.5px solid #e2e8f0; border-radius:8px;">
                        </div>
                        <button type="submit" name="upload_resume" class="btn btn-primary w-100" style="background:linear-gradient(135deg,#6366f1,#8b5cf6,#a78bfa); border:none; border-radius:9999px; padding:0.75rem; font-weight:600;">
                            <i class="fas fa-upload me-2"></i>Upload Resume
                        </button>
                    </form>
                </div>
            </div>

            <!-- Current Resume -->
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                    <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-file-alt me-2" style="color:#10b981;"></i>Current Resume</h4>
                </div>
                <div style="padding:1.5rem;">
                    <?php if ($latestResume->num_rows > 0):
                        $resumeData = $latestResume->fetch_assoc(); ?>
                        <div style="display:flex; align-items:center; gap:1rem;">
                            <div style="width:48px; height:48px; border-radius:12px; background:#eef2ff; display:flex; align-items:center; justify-content:center; color:#6366f1; font-size:1.2rem; flex-shrink:0;"><i class="fas fa-file-pdf"></i></div>
                            <div style="flex:1;">
                                <p style="margin:0; font-weight:600; font-size:0.9rem; color:#334155;"><?php echo basename($resumeData['file_path']); ?></p>
                                <p style="margin:0; font-size:0.8rem; color:#94a3b8;">Uploaded on <?php echo date('d M Y, h:i A', strtotime($resumeData['uploaded_at'])); ?></p>
                            </div>
                            <a href="<?php echo $resumeData['file_path']; ?>" target="_blank" style="display:inline-flex; align-items:center; gap:0.35rem; padding:0.4rem 1rem; border-radius:9999px; font-weight:600; font-size:0.8rem; background:linear-gradient(135deg,#3b82f6,#6366f1); color:#fff; text-decoration:none;"><i class="fas fa-download"></i> Download</a>
                        </div>
                    <?php else: ?>
                        <div style="text-align:center; color:#94a3b8; padding:1rem 0;">
                            <i class="fas fa-file-circle-xmark" style="font-size:2rem; margin-bottom:0.5rem; display:block; opacity:0.5;"></i>
                            <p style="margin:0; font-size:0.9rem;">No resume uploaded yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("../includes/footer.php"); ?>
</body>
</html>