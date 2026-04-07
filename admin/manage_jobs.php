<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['admin_id'])) { header("Location: ../login.php"); exit(); }
if (isset($_GET['delete_id'])) {
    $conn->query("DELETE FROM jobs WHERE job_id='".$_GET['delete_id']."'");
    header("Location: manage_jobs.php?msg=deleted"); exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs — NextGen</title>
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
            <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;">Manage Jobs</h2>
            <p style="color:#64748b; font-size:0.9rem; margin-bottom:1.5rem;">View and manage all job postings</p>
            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success d-flex align-items-center gap-2" style="border-radius:12px; font-size:0.9rem;"><i class="fas fa-check-circle"></i> Job <?php echo htmlspecialchars($_GET['msg']); ?> successfully!</div>
            <?php endif; ?>
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                    <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-briefcase me-2" style="color:#6366f1;"></i>All Jobs</h4>
                </div>
                <div class="table-responsive">
                    <table style="border-collapse:separate; border-spacing:0; width:100%;">
                        <thead><tr>
                            <?php foreach(['ID','Title','Company','Skills','CGPA','Posted','Actions'] as $h): ?>
                            <th style="background:#1e293b; color:#fff; font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1rem; border:none; white-space:nowrap;"><?php echo $h; ?></th>
                            <?php endforeach; ?>
                        </tr></thead>
                        <tbody>
                            <?php
                            $res = $conn->query("SELECT jobs.*, companies.name AS company_name FROM jobs JOIN companies ON jobs.company_id = companies.company_id ORDER BY jobs.job_id DESC");
                            if ($res->num_rows > 0) {
                                while ($j = $res->fetch_assoc()) {
                                    echo '<tr>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.85rem; color:#94a3b8;">#'.$j['job_id'].'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#334155; font-weight:600;">'.htmlspecialchars($j['title']).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.85rem; color:#475569;">'.htmlspecialchars($j['company_name']).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.85rem; color:#475569; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'.htmlspecialchars($j['required_skills']).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#475569;">'.$j['cgpa_requirement'].'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.85rem; color:#94a3b8;">'.date('d M Y', strtotime($j['created_at'])).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9;">
                                            <div style="display:flex; gap:0.35rem;">
                                                <a href="edit_job.php?id='.$j['job_id'].'" style="width:30px; height:30px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:0.8rem; background:rgba(245,158,11,0.1); color:#f59e0b; text-decoration:none;" title="Edit"><i class="fas fa-edit"></i></a>
                                                <a href="manage_jobs.php?delete_id='.$j['job_id'].'" onclick="return confirm(\'Delete?\')" style="width:30px; height:30px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:0.8rem; background:rgba(239,68,68,0.1); color:#ef4444; text-decoration:none;" title="Delete"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>';
                                }
                            } else { echo '<tr><td colspan="7" style="padding:2rem; text-align:center; color:#94a3b8;">No jobs found</td></tr>'; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("../includes/footer.php"); ?>
</body></html>