<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['company_id'])) { header("Location: ../login.php"); exit(); }
$company_id = $_SESSION['company_id'];
$companyName = $_SESSION['company_name'] ?? 'Company';

$jobsPosted = $conn->query("SELECT COUNT(*) AS total FROM jobs WHERE company_id='$company_id'")->fetch_assoc()['total'];
$totalApplicants = $conn->query("SELECT COUNT(*) AS total FROM applications JOIN jobs ON applications.job_id = jobs.job_id WHERE jobs.company_id='$company_id'")->fetch_assoc()['total'];
$studentsPlaced = $conn->query("SELECT COUNT(*) AS total FROM placements WHERE company_id='$company_id'")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard — NextGen</title>
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
            <div style="background: linear-gradient(135deg, #059669, #10b981, #34d399); border-radius:16px; padding:2rem; color:#fff; margin-bottom:1.5rem; position:relative; overflow:hidden;">
                <div style="position:absolute; top:-30px; right:-30px; width:150px; height:150px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                <h2 style="color:#fff; font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;">Welcome, <?php echo htmlspecialchars($companyName); ?>!</h2>
                <p style="color:rgba(255,255,255,0.75); margin:0; font-size:0.95rem;">Manage your job postings and applicants from here.</p>
            </div>

            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div style="border-radius:16px; padding:1.5rem; color:#fff; position:relative; overflow:hidden; background:linear-gradient(135deg, #6366f1, #8b5cf6, #a78bfa); min-height:130px;">
                        <div style="position:absolute; top:-20px; right:-20px; width:80px; height:80px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
                        <div style="width:42px; height:42px; border-radius:12px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:1.1rem; margin-bottom:0.75rem;"><i class="fas fa-briefcase"></i></div>
                        <div style="font-size:1.75rem; font-weight:800; line-height:1;"><?php echo $jobsPosted; ?></div>
                        <div style="font-size:0.8rem; opacity:0.85; font-weight:500; margin-top:0.25rem;">Jobs Posted</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="border-radius:16px; padding:1.5rem; color:#fff; position:relative; overflow:hidden; background:linear-gradient(135deg, #059669, #10b981, #34d399); min-height:130px;">
                        <div style="position:absolute; top:-20px; right:-20px; width:80px; height:80px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
                        <div style="width:42px; height:42px; border-radius:12px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:1.1rem; margin-bottom:0.75rem;"><i class="fas fa-users"></i></div>
                        <div style="font-size:1.75rem; font-weight:800; line-height:1;"><?php echo $totalApplicants; ?></div>
                        <div style="font-size:0.8rem; opacity:0.85; font-weight:500; margin-top:0.25rem;">Total Applicants</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="border-radius:16px; padding:1.5rem; color:#fff; position:relative; overflow:hidden; background:linear-gradient(135deg, #f59e0b, #f97316); min-height:130px;">
                        <div style="position:absolute; top:-20px; right:-20px; width:80px; height:80px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
                        <div style="width:42px; height:42px; border-radius:12px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:1.1rem; margin-bottom:0.75rem;"><i class="fas fa-trophy"></i></div>
                        <div style="font-size:1.75rem; font-weight:800; line-height:1;"><?php echo $studentsPlaced; ?></div>
                        <div style="font-size:0.8rem; opacity:0.85; font-weight:500; margin-top:0.25rem;">Students Placed</div>
                    </div>
                </div>
            </div>

            <!-- Recent Jobs -->
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between;">
                    <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-briefcase me-2" style="color:#6366f1;"></i>Recent Job Postings</h4>
                    <a href="post_job.php" style="display:inline-flex; align-items:center; gap:0.35rem; padding:0.4rem 1rem; border-radius:9999px; font-weight:600; font-size:0.8rem; background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; text-decoration:none;"><i class="fas fa-plus"></i> Post New</a>
                </div>
                <div class="table-responsive">
                    <table style="border-collapse:separate; border-spacing:0; width:100%;">
                        <thead>
                            <tr>
                                <th style="background:#1e293b; color:#fff; font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1rem; border:none;">Title</th>
                                <th style="background:#1e293b; color:#fff; font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1rem; border:none;">Skills Required</th>
                                <th style="background:#1e293b; color:#fff; font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1rem; border:none;">CGPA</th>
                                <th style="background:#1e293b; color:#fff; font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1rem; border:none;">Posted On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = $conn->query("SELECT * FROM jobs WHERE company_id='$company_id' ORDER BY created_at DESC LIMIT 5");
                            if ($res->num_rows > 0) {
                                while ($job = $res->fetch_assoc()) {
                                    echo '<tr>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#334155; font-weight:600;">'.htmlspecialchars($job['title']).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#475569;">'.htmlspecialchars($job['required_skills']).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#475569;">'.$job['cgpa_requirement'].'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#94a3b8;">'.date('d M Y', strtotime($job['created_at'])).'</td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4" style="padding:2rem; text-align:center; color:#94a3b8;"><i class="fas fa-folder-open" style="font-size:2rem; display:block; margin-bottom:0.5rem; opacity:0.5;"></i>No jobs posted yet</td></tr>';
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