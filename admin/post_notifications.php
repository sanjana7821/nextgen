<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['admin_id'])) { header("Location: ../login.php"); exit(); }
$postMsg = '';
if (isset($_POST['post_notification'])) {
    $message = $_POST['message'];
    $type = $_POST['type'];
    $target_type = $_POST['target_type'];
    if ($target_type == 'all') {
        $conn->query("INSERT INTO notifications (student_id, message, type) VALUES (NULL, '$message', '$type')");
    } else {
        $student_ids = explode(",", $_POST['specific_ids']);
        foreach ($student_ids as $id) { $id = trim($id); if (is_numeric($id)) { $conn->query("INSERT INTO notifications (student_id, message, type) VALUES ('$id', '$message', '$type')"); } }
    }
    $postMsg = 'success';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Notifications — NextGen</title>
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
            <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;">Post Notifications</h2>
            <p style="color:#64748b; font-size:0.9rem; margin-bottom:1.5rem;">Send announcements to students</p>
            <?php if ($postMsg === 'success'): ?>
                <div class="alert alert-success d-flex align-items-center gap-2" style="border-radius:12px; font-size:0.9rem;"><i class="fas fa-check-circle"></i> Notification posted successfully!</div>
            <?php endif; ?>
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                    <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-bell me-2" style="color:#6366f1;"></i>New Notification</h4>
                </div>
                <div style="padding:1.5rem;">
                    <form method="POST">
                        <div class="mb-3">
                            <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.4rem;">Message</label>
                            <textarea name="message" class="form-control" rows="3" required placeholder="Write your notification message..." style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.7rem 1rem;"></textarea>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.4rem;">Type</label>
                                <select name="type" class="form-select" style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.7rem 1rem;">
                                    <option value="general">General</option>
                                    <option value="placement_drive">Placement Drive</option>
                                    <option value="announcement">Announcement</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.4rem;">Target</label>
                                <select name="target_type" class="form-select" id="targetSelect" style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.7rem 1rem;">
                                    <option value="all">All Students</option>
                                    <option value="specific">Specific Students</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3" id="specificIds" style="display:none;">
                            <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.4rem;">Student IDs <small style="color:#94a3b8;">(comma-separated)</small></label>
                            <input type="text" name="specific_ids" class="form-control" placeholder="e.g., 1, 2, 3" style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.7rem 1rem;">
                        </div>
                        <button type="submit" name="post_notification" class="btn btn-primary w-100" style="background:linear-gradient(135deg,#6366f1,#8b5cf6,#a78bfa); border:none; border-radius:9999px; padding:0.75rem; font-weight:600;">
                            <i class="fas fa-paper-plane me-2"></i>Post Notification
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('targetSelect').addEventListener('change', function() {
    document.getElementById('specificIds').style.display = this.value == 'specific' ? 'block' : 'none';
});
</script>
<?php include("../includes/footer.php"); ?>
</body></html>