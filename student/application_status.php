<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['student_id'])) { header("Location: ../login.php"); exit(); }
$student_id = $_SESSION['student_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status — NextGen</title>
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
            <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;">My Applications</h2>
            <p style="color:#64748b; font-size:0.9rem; margin-bottom:1.5rem;">Track the status of all your job applications</p>

            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                    <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-clipboard-check me-2" style="color:#6366f1;"></i>Application History</h4>
                </div>
                <div class="table-responsive">
                    <table style="border-collapse:separate; border-spacing:0; width:100%;">
                        <thead>
                            <tr>
                                <th style="background:#1e293b; color:#fff; font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1rem; border:none;">Job Title</th>
                                <th style="background:#1e293b; color:#fff; font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1rem; border:none;">Company</th>
                                <th style="background:#1e293b; color:#fff; font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1rem; border:none;">Status</th>
                                <th style="background:#1e293b; color:#fff; font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1rem; border:none;">Applied Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = $conn->query("SELECT applications.*, jobs.title, companies.name AS company_name FROM applications JOIN jobs ON applications.job_id = jobs.job_id JOIN companies ON jobs.company_id = companies.company_id WHERE applications.student_id='$student_id' ORDER BY applications.applied_at DESC");
                            if ($res->num_rows > 0) {
                                while ($app = $res->fetch_assoc()) {
                                    $status = strtolower($app['status']);
                                    $badgeBg = 'rgba(99,102,241,0.1)'; $badgeColor = '#6366f1';
                                    if ($status === 'applied') { $badgeBg = 'rgba(59,130,246,0.1)'; $badgeColor = '#3b82f6'; }
                                    elseif ($status === 'shortlisted') { $badgeBg = 'rgba(245,158,11,0.1)'; $badgeColor = '#f59e0b'; }
                                    elseif ($status === 'selected') { $badgeBg = 'rgba(16,185,129,0.1)'; $badgeColor = '#10b981'; }
                                    elseif ($status === 'rejected') { $badgeBg = 'rgba(239,68,68,0.1)'; $badgeColor = '#ef4444'; }

                                    echo '<tr>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#334155; font-weight:600;">'.htmlspecialchars($app['title']).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#475569;">'.htmlspecialchars($app['company_name']).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9;"><span style="display:inline-flex; align-items:center; padding:0.3rem 0.8rem; border-radius:9999px; font-size:0.75rem; font-weight:600; background:'.$badgeBg.'; color:'.$badgeColor.';">'.ucfirst($app['status']).'</span></td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#94a3b8;">'.date('d M Y', strtotime($app['applied_at'])).'</td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4" style="padding:2rem; text-align:center; color:#94a3b8;"><i class="fas fa-folder-open" style="font-size:2rem; display:block; margin-bottom:0.5rem; opacity:0.5;"></i>No applications yet. Browse jobs to get started!</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("../includes/footer.php"); ?>
</body>
</html>