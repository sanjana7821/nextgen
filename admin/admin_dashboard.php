<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['admin_id'])) { header("Location: ../login.php"); exit(); }
$totalStudents = $conn->query("SELECT COUNT(*) AS t FROM students")->fetch_assoc()['t'];
$totalCompanies = $conn->query("SELECT COUNT(*) AS t FROM companies")->fetch_assoc()['t'];
$totalJobs = $conn->query("SELECT COUNT(*) AS t FROM jobs")->fetch_assoc()['t'];
$totalApps = $conn->query("SELECT COUNT(*) AS t FROM applications")->fetch_assoc()['t'];
$totalPlaced = $conn->query("SELECT COUNT(*) AS t FROM placements")->fetch_assoc()['t'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — NextGen</title>
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
            <!-- Welcome Banner -->
            <div style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 30%, #4338ca 70%, #6366f1 100%); border-radius:16px; padding:2rem; color:#fff; margin-bottom:1.5rem; position:relative; overflow:hidden;">
                <div style="position:absolute; top:-30px; right:-30px; width:150px; height:150px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                <h2 style="color:#fff; font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;"><i class="fas fa-shield-alt me-2"></i>Admin Dashboard</h2>
                <p style="color:rgba(255,255,255,0.75); margin:0; font-size:0.95rem;">Complete overview of your placement system</p>
            </div>

            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <?php
                $stats = [
                    ['label'=>'Students','value'=>$totalStudents,'icon'=>'fa-user-graduate','gradient'=>'linear-gradient(135deg,#6366f1,#8b5cf6,#a78bfa)'],
                    ['label'=>'Companies','value'=>$totalCompanies,'icon'=>'fa-building','gradient'=>'linear-gradient(135deg,#f59e0b,#f97316)'],
                    ['label'=>'Jobs Posted','value'=>$totalJobs,'icon'=>'fa-briefcase','gradient'=>'linear-gradient(135deg,#059669,#10b981,#34d399)'],
                    ['label'=>'Applications','value'=>$totalApps,'icon'=>'fa-paper-plane','gradient'=>'linear-gradient(135deg,#ef4444,#f97316)'],
                    ['label'=>'Placed','value'=>$totalPlaced,'icon'=>'fa-trophy','gradient'=>'linear-gradient(135deg,#3b82f6,#6366f1)'],
                ];
                foreach ($stats as $s): ?>
                <div class="col-6 col-md">
                    <div style="border-radius:16px; padding:1.25rem; color:#fff; position:relative; overflow:hidden; background:<?php echo $s['gradient']; ?>; min-height:120px;">
                        <div style="position:absolute; top:-20px; right:-20px; width:70px; height:70px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
                        <div style="width:38px; height:38px; border-radius:10px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:1rem; margin-bottom:0.6rem;"><i class="fas <?php echo $s['icon']; ?>"></i></div>
                        <div style="font-size:1.5rem; font-weight:800; line-height:1;"><?php echo $s['value']; ?></div>
                        <div style="font-size:0.75rem; opacity:0.85; font-weight:500; margin-top:0.2rem;"><?php echo $s['label']; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Placement Statistics -->
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                    <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-chart-bar me-2" style="color:#6366f1;"></i>Yearly Placement Statistics</h4>
                </div>
                <div class="table-responsive">
                    <table style="border-collapse:separate; border-spacing:0; width:100%;">
                        <thead>
                            <tr>
                                <?php foreach(['Year','Total Students','Placed','Companies','Jobs'] as $h): ?>
                                <th style="background:#1e293b; color:#fff; font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1rem; border:none;"><?php echo $h; ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = $conn->query("SELECT YEAR(placed_at) AS year, COUNT(DISTINCT student_id) AS total_students, COUNT(*) AS placed_students, COUNT(DISTINCT company_id) AS companies_participated, COUNT(DISTINCT job_id) AS jobs_posted FROM placements GROUP BY YEAR(placed_at) ORDER BY year DESC");
                            if ($res->num_rows > 0) {
                                while ($row = $res->fetch_assoc()) {
                                    echo '<tr>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; font-weight:700; color:#334155;">'.$row['year'].'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#475569;">'.$row['total_students'].'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9;"><span style="background:rgba(16,185,129,0.1); color:#10b981; padding:0.2rem 0.6rem; border-radius:9999px; font-size:0.8rem; font-weight:600;">'.$row['placed_students'].'</span></td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#475569;">'.$row['companies_participated'].'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#475569;">'.$row['jobs_posted'].'</td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5" style="padding:2rem; text-align:center; color:#94a3b8;"><i class="fas fa-chart-line" style="font-size:2rem; display:block; margin-bottom:0.5rem; opacity:0.5;"></i>No placement data available</td></tr>';
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