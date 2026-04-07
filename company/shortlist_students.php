<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['company_id'])) { header("Location: ../login.php"); exit(); }
$company_id = $_SESSION['company_id'];

if (isset($_GET['shortlist_id'])) {
    $application_id = $_GET['shortlist_id'];
    $check = $conn->query("SELECT applications.application_id FROM applications JOIN jobs ON applications.job_id = jobs.job_id WHERE applications.application_id='$application_id' AND jobs.company_id='$company_id'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE applications SET status='Shortlisted' WHERE application_id='$application_id'");
        $app = $conn->query("SELECT student_id, job_id FROM applications WHERE application_id='$application_id'")->fetch_assoc();
        $job = $conn->query("SELECT title FROM jobs WHERE job_id='".$app['job_id']."'")->fetch_assoc();
        $conn->query("INSERT INTO notifications (student_id, message, type) VALUES ('".$app['student_id']."', 'Congratulations! You have been shortlisted for: ".$job['title']."', 'status_update')");
        header("Location: shortlist_students.php?msg=shortlisted"); exit();
    }
}
if (isset($_GET['select_id'])) {
    $application_id = $_GET['select_id'];
    $check = $conn->query("SELECT applications.application_id FROM applications JOIN jobs ON applications.job_id = jobs.job_id WHERE applications.application_id='$application_id' AND jobs.company_id='$company_id'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE applications SET status='Selected' WHERE application_id='$application_id'");
        $app = $conn->query("SELECT student_id, job_id FROM applications WHERE application_id='$application_id'")->fetch_assoc();
        $job = $conn->query("SELECT title FROM jobs WHERE job_id='".$app['job_id']."'")->fetch_assoc();
        $conn->query("INSERT INTO notifications (student_id, message, type) VALUES ('".$app['student_id']."', 'Congratulations! You have been selected for: ".$job['title']."', 'status_update')");
        $conn->query("INSERT INTO placements (student_id, job_id, company_id) VALUES ('".$app['student_id']."', '".$app['job_id']."', '$company_id')");
        header("Location: shortlist_students.php?msg=selected"); exit();
    }
}
if (isset($_GET['reject_id'])) {
    $application_id = $_GET['reject_id'];
    $check = $conn->query("SELECT applications.application_id FROM applications JOIN jobs ON applications.job_id = jobs.job_id WHERE applications.application_id='$application_id' AND jobs.company_id='$company_id'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE applications SET status='Rejected' WHERE application_id='$application_id'");
        header("Location: shortlist_students.php?msg=rejected"); exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shortlist Students — NextGen</title>
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
            <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;">Shortlist Students</h2>
            <p style="color:#64748b; font-size:0.9rem; margin-bottom:1.5rem;">Filter and manage student applications</p>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success d-flex align-items-center gap-2" style="border-radius:12px; font-size:0.9rem;"><i class="fas fa-check-circle"></i> Student <?php echo htmlspecialchars($_GET['msg']); ?> successfully!</div>
            <?php endif; ?>

            <!-- Filter -->
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.5rem; margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06);">
                <form method="GET" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.3rem;">Skill Filter</label>
                        <input type="text" name="skill" class="form-control" placeholder="e.g. Python" value="<?php echo htmlspecialchars($_GET['skill'] ?? ''); ?>" style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.6rem 1rem; font-size:0.9rem;">
                    </div>
                    <div class="col-md-4">
                        <label style="font-weight:600; font-size:0.85rem; color:#334155; margin-bottom:0.3rem;">Minimum CGPA</label>
                        <input type="number" step="0.01" name="cgpa" class="form-control" placeholder="e.g. 7.0" value="<?php echo htmlspecialchars($_GET['cgpa'] ?? ''); ?>" style="border:1.5px solid #e2e8f0; border-radius:8px; padding:0.6rem 1rem; font-size:0.9rem;">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" style="display:inline-flex; align-items:center; gap:0.35rem; width:100%; justify-content:center; padding:0.65rem 1rem; border-radius:9999px; font-weight:600; font-size:0.9rem; background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; border:none; cursor:pointer;"><i class="fas fa-filter"></i> Apply Filter</button>
                    </div>
                </form>
            </div>

            <!-- Results Table -->
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                    <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-user-check me-2" style="color:#6366f1;"></i>Filtered Applicants</h4>
                </div>
                <div class="table-responsive">
                    <table style="border-collapse:separate; border-spacing:0; width:100%;">
                        <thead>
                            <tr>
                                <?php foreach(['Job','Student','CGPA','Skills','Status','Actions'] as $h): ?>
                                <th style="background:#1e293b; color:#fff; font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1rem; border:none; white-space:nowrap;"><?php echo $h; ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT applications.*, jobs.title, students.name, students.email, students.cgpa, students.skills FROM applications JOIN jobs ON applications.job_id = jobs.job_id JOIN students ON applications.student_id = students.student_id WHERE jobs.company_id='$company_id' AND applications.status NOT IN ('Rejected','Selected')";
                            if (!empty($_GET['skill'])) { $query .= " AND students.skills LIKE '%".$_GET['skill']."%'"; }
                            if (!empty($_GET['cgpa'])) { $query .= " AND students.cgpa >= '".$_GET['cgpa']."'"; }
                            $res = $conn->query($query);
                            if ($res->num_rows > 0) {
                                while ($app = $res->fetch_assoc()) {
                                    $status = $app['status'];
                                    $badgeBg = 'rgba(59,130,246,0.1)'; $badgeColor = '#3b82f6';
                                    if ($status === 'Shortlisted') { $badgeBg = 'rgba(245,158,11,0.1)'; $badgeColor = '#f59e0b'; }
                                    echo '<tr>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; font-weight:600; color:#334155;">'.htmlspecialchars($app['title']).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9;"><div style="font-size:0.9rem; font-weight:600; color:#334155;">'.htmlspecialchars($app['name']).'</div><div style="font-size:0.8rem; color:#94a3b8;">'.htmlspecialchars($app['email']).'</div></td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#475569;">'.$app['cgpa'].'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.85rem; color:#475569;">'.htmlspecialchars($app['skills']).'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9;"><span style="display:inline-flex; padding:0.3rem 0.8rem; border-radius:9999px; font-size:0.75rem; font-weight:600; background:'.$badgeBg.'; color:'.$badgeColor.';">'.$status.'</span></td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9;">
                                            <div style="display:flex; gap:0.35rem;">
                                                <a href="shortlist_students.php?shortlist_id='.$app['application_id'].'" style="width:30px; height:30px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:0.8rem; background:rgba(59,130,246,0.1); color:#3b82f6; text-decoration:none;" title="Shortlist"><i class="fas fa-list"></i></a>
                                                <a href="shortlist_students.php?select_id='.$app['application_id'].'" style="width:30px; height:30px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:0.8rem; background:rgba(16,185,129,0.1); color:#10b981; text-decoration:none;" title="Select"><i class="fas fa-check"></i></a>
                                                <a href="shortlist_students.php?reject_id='.$app['application_id'].'" onclick="return confirm(\'Are you sure?\')" style="width:30px; height:30px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:0.8rem; background:rgba(239,68,68,0.1); color:#ef4444; text-decoration:none;" title="Reject"><i class="fas fa-times"></i></a>
                                            </div>
                                        </td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="6" style="padding:2rem; text-align:center; color:#94a3b8;"><i class="fas fa-search" style="font-size:2rem; display:block; margin-bottom:0.5rem; opacity:0.5;"></i>No applicants found matching your criteria</td></tr>';
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