<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['student_id'])) { header("Location: ../login.php"); exit(); }
$student_id = $_SESSION['student_id'];
$res = $conn->query("SELECT * FROM students WHERE student_id='$student_id'");
$student = $res->fetch_assoc();
$updateMsg = '';

if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $cgpa = $_POST['cgpa'];
    $skills = $_POST['skills'];
    $projects = $_POST['projects'];
    $education = $_POST['education'];
    $experience = $_POST['experience'];
    $sql = "UPDATE students SET name='$name', cgpa='$cgpa', skills='$skills', projects='$projects', education='$education', experience='$experience' WHERE student_id='$student_id'";
    if ($conn->query($sql) === TRUE) {
        $updateMsg = 'success';
        $student = $conn->query("SELECT * FROM students WHERE student_id='$student_id'")->fetch_assoc();
    } else {
        $updateMsg = 'error';
    }
}

// Profile completeness
$fields = ['name','cgpa','skills','projects','education','experience'];
$filled = 0;
foreach ($fields as $f) { if (!empty($student[$f])) $filled++; }
$completeness = round(($filled / count($fields)) * 100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile — NextGen</title>
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
            <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;">Edit Profile</h2>
            <p style="color:#64748b; font-size:0.9rem; margin-bottom:1.5rem;">Keep your profile updated for better job recommendations</p>

            <?php if ($updateMsg === 'success'): ?>
                <div class="alert alert-success d-flex align-items-center gap-2" style="border-radius:12px; font-size:0.9rem;"><i class="fas fa-check-circle"></i> Profile updated successfully!</div>
            <?php elseif ($updateMsg === 'error'): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2" style="border-radius:12px; font-size:0.9rem;"><i class="fas fa-exclamation-circle"></i> Failed to update profile.</div>
            <?php endif; ?>

            <!-- Profile Completeness -->
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.5rem; margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                    <span style="font-weight:600; font-size:0.9rem; color:#334155;">Profile Completeness</span>
                    <span style="font-weight:700; font-size:0.9rem; color:<?php echo $completeness == 100 ? '#10b981' : '#f59e0b'; ?>;"><?php echo $completeness; ?>%</span>
                </div>
                <div style="width:100%; height:8px; border-radius:9999px; background:#e2e8f0;">
                    <div style="width:<?php echo $completeness; ?>%; height:100%; border-radius:9999px; background:<?php echo $completeness == 100 ? 'linear-gradient(135deg,#10b981,#34d399)' : 'linear-gradient(135deg,#6366f1,#a78bfa)'; ?>; transition:width 0.5s;"></div>
                </div>
            </div>

            <!-- Profile Form -->
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                    <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-user-edit me-2" style="color:#6366f1;"></i>Personal Details</h4>
                </div>
                <div style="padding:1.5rem;">
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.4rem;">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" required style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.7rem 1rem;">
                            </div>
                            <div class="col-md-6">
                                <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.4rem;">CGPA</label>
                                <input type="number" step="0.01" name="cgpa" class="form-control" value="<?php echo $student['cgpa']; ?>" required style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.7rem 1rem;">
                            </div>
                            <div class="col-12">
                                <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.4rem;">Skills <small style="color:#94a3b8;">(comma separated)</small></label>
                                <textarea name="skills" class="form-control" rows="2" style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.7rem 1rem;"><?php echo htmlspecialchars($student['skills']); ?></textarea>
                            </div>
                            <div class="col-12">
                                <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.4rem;">Projects</label>
                                <textarea name="projects" class="form-control" rows="2" style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.7rem 1rem;"><?php echo htmlspecialchars($student['projects']); ?></textarea>
                            </div>
                            <div class="col-12">
                                <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.4rem;">Education</label>
                                <textarea name="education" class="form-control" rows="2" style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.7rem 1rem;"><?php echo htmlspecialchars($student['education']); ?></textarea>
                            </div>
                            <div class="col-12">
                                <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.4rem;">Experience</label>
                                <textarea name="experience" class="form-control" rows="2" style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.7rem 1rem;"><?php echo htmlspecialchars($student['experience']); ?></textarea>
                            </div>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary w-100 mt-4" style="background:linear-gradient(135deg,#6366f1,#8b5cf6,#a78bfa); border:none; border-radius:9999px; padding:0.75rem; font-weight:600;">
                            <i class="fas fa-save me-2"></i>Update Profile
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