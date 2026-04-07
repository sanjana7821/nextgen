<?php include("../config/db.php"); ?>
<?php if (!isset($_SESSION['admin_id'])) { header("Location: ../login.php"); exit(); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement Reports — NextGen</title>
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
            <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;">Placement Reports</h2>
            <p style="color:#64748b; font-size:0.9rem; margin-bottom:1.5rem;">Aggregate placement data by company and job</p>

            <!-- By Company/Job -->
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden; margin-bottom:1.5rem;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                    <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-chart-pie me-2" style="color:#6366f1;"></i>Placements by Company & Job</h4>
                </div>
                <div class="table-responsive">
                    <table style="border-collapse:separate; border-spacing:0; width:100%;">
                        <thead><tr>
                            <?php foreach(['Company','Job Title','Students Selected'] as $h): ?>
                            <th style="background:#1e293b; color:#fff; font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1rem; border:none;"><?php echo $h; ?></th>
                            <?php endforeach; ?>
                        </tr></thead>
                        <tbody>
                            <?php
                            $res = $conn->query("SELECT companies.name AS company_name, jobs.title, COUNT(placements.placement_id) AS selected_count FROM placements JOIN jobs ON placements.job_id = jobs.job_id JOIN companies ON placements.company_id = companies.company_id GROUP BY placements.job_id");
                            if ($res->num_rows > 0) {
                                while ($r = $res->fetch_assoc()) {
                                    echo '<tr>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#334155; font-weight:600;">'.htmlspecialchars($r['company_name']).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.85rem; color:#475569;">'.htmlspecialchars($r['title']).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9;"><span style="background:rgba(16,185,129,0.1); color:#10b981; padding:0.2rem 0.6rem; border-radius:9999px; font-size:0.8rem; font-weight:600;">'.$r['selected_count'].'</span></td>
                                    </tr>';
                                }
                            } else { echo '<tr><td colspan="3" style="padding:2rem; text-align:center; color:#94a3b8;">No placement records</td></tr>'; } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Placed Students -->
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                    <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-trophy me-2" style="color:#f59e0b;"></i>All Placed Students</h4>
                </div>
                <div class="table-responsive">
                    <table style="border-collapse:separate; border-spacing:0; width:100%;">
                        <thead><tr>
                            <?php foreach(['Student','Job Title','Company','Placed On'] as $h): ?>
                            <th style="background:#1e293b; color:#fff; font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1rem; border:none;"><?php echo $h; ?></th>
                            <?php endforeach; ?>
                        </tr></thead>
                        <tbody>
                            <?php
                            $placed = $conn->query("SELECT students.name, jobs.title, companies.name AS company_name, placements.placed_at FROM placements JOIN students ON placements.student_id = students.student_id JOIN jobs ON placements.job_id = jobs.job_id JOIN companies ON placements.company_id = companies.company_id ORDER BY placements.placed_at DESC");
                            if ($placed->num_rows > 0) {
                                while ($p = $placed->fetch_assoc()) {
                                    echo '<tr>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#334155; font-weight:600;">'.htmlspecialchars($p['name']).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.85rem; color:#475569;">'.htmlspecialchars($p['title']).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.85rem; color:#475569;">'.htmlspecialchars($p['company_name']).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.85rem; color:#94a3b8;">'.date('d M Y', strtotime($p['placed_at'])).'</td>
                                    </tr>';
                                }
                            } else { echo '<tr><td colspan="4" style="padding:2rem; text-align:center; color:#94a3b8;">No students placed yet</td></tr>'; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("../includes/footer.php"); ?>
</body></html>