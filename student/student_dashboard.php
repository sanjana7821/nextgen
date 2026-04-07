<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}
$student_id = $_SESSION['student_id'];
$studentData = $conn->query("SELECT * FROM students WHERE student_id='$student_id'")->fetch_assoc();
$studentName = $studentData['name'] ?? 'Student';

// Resume Score
$score = 0;
if (!empty($studentData['skills'])) $score += 25;
if (!empty($studentData['projects'])) $score += 25;
if (!empty($studentData['education'])) $score += 25;
if (!empty($studentData['experience'])) $score += 25;

// Job Recommendations
$skills = array_map('trim', explode(",", $studentData['skills'] ?? ''));
$jobs = $conn->query("SELECT title, required_skills FROM jobs LIMIT 5");
$recommendations = [];
while ($job = $jobs->fetch_assoc()) {
    $jobSkills = array_map('trim', explode(",", $job['required_skills']));
    $match = count(array_intersect($skills, $jobSkills));
    if ($match > 0) $recommendations[] = $job['title'];
}

// Skill Gap
$latestJob = $conn->query("SELECT required_skills FROM jobs ORDER BY job_id DESC LIMIT 1");
$missingSkills = [];
if ($jd = $latestJob->fetch_assoc()) {
    $jobSkills = array_map('trim', explode(",", $jd['required_skills']));
    $missingSkills = array_diff($jobSkills, $skills);
}

// Placement Prediction
$cgpa = $studentData['cgpa'] ?? 0;
$prediction = "Low";
$predColor = "#ef4444";
if ($cgpa >= 7 && $score >= 75) { $prediction = "High"; $predColor = "#10b981"; }
elseif ($cgpa >= 6 && $score >= 50) { $prediction = "Medium"; $predColor = "#f59e0b"; }

// Applications count
$appCount = $conn->query("SELECT COUNT(*) as c FROM applications WHERE student_id='$student_id'")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard — NextGen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; }
    </style>
</head>
<body>

<?php include("../includes/navbar.php"); ?>

<div class="container-fluid" style="max-width: 1400px; margin: 0 auto; padding: 1.5rem;">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden; position:sticky; top:90px;">
                <?php include("sidebar.php"); ?>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Welcome Banner -->
            <div style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a78bfa 100%); border-radius:16px; padding:2rem; color:#fff; margin-bottom:1.5rem; position:relative; overflow:hidden;">
                <div style="position:absolute; top:-30px; right:-30px; width:150px; height:150px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                <h2 style="color:#fff; font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;">Welcome back, <?php echo htmlspecialchars($studentName); ?>!</h2>
                <p style="color:rgba(255,255,255,0.75); margin:0; font-size:0.95rem;">Here's an overview of your placement journey.</p>
            </div>

            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div style="border-radius:16px; padding:1.5rem; color:#fff; position:relative; overflow:hidden; background:linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a78bfa 100%); min-height:130px;">
                        <div style="position:absolute; top:-20px; right:-20px; width:80px; height:80px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
                        <div style="width:42px; height:42px; border-radius:12px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:1.1rem; margin-bottom:0.75rem;"><i class="fas fa-file-alt"></i></div>
                        <div style="font-size:1.75rem; font-weight:800; line-height:1;"><?php echo $score; ?>%</div>
                        <div style="font-size:0.8rem; opacity:0.85; font-weight:500; margin-top:0.25rem;">Resume Score</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div style="border-radius:16px; padding:1.5rem; color:#fff; position:relative; overflow:hidden; background:linear-gradient(135deg, #059669, #10b981, #34d399); min-height:130px;">
                        <div style="position:absolute; top:-20px; right:-20px; width:80px; height:80px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
                        <div style="width:42px; height:42px; border-radius:12px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:1.1rem; margin-bottom:0.75rem;"><i class="fas fa-star"></i></div>
                        <div style="font-size:1.75rem; font-weight:800; line-height:1;"><?php echo count($recommendations); ?></div>
                        <div style="font-size:0.8rem; opacity:0.85; font-weight:500; margin-top:0.25rem;">Job Matches</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div style="border-radius:16px; padding:1.5rem; color:#fff; position:relative; overflow:hidden; background:linear-gradient(135deg, #f59e0b, #f97316); min-height:130px;">
                        <div style="position:absolute; top:-20px; right:-20px; width:80px; height:80px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
                        <div style="width:42px; height:42px; border-radius:12px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:1.1rem; margin-bottom:0.75rem;"><i class="fas fa-paper-plane"></i></div>
                        <div style="font-size:1.75rem; font-weight:800; line-height:1;"><?php echo $appCount; ?></div>
                        <div style="font-size:0.8rem; opacity:0.85; font-weight:500; margin-top:0.25rem;">Applications</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div style="border-radius:16px; padding:1.5rem; color:#fff; position:relative; overflow:hidden; background:linear-gradient(135deg, <?php echo $predColor; ?>, <?php echo $predColor; ?>cc); min-height:130px;">
                        <div style="position:absolute; top:-20px; right:-20px; width:80px; height:80px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
                        <div style="width:42px; height:42px; border-radius:12px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:1.1rem; margin-bottom:0.75rem;"><i class="fas fa-chart-line"></i></div>
                        <div style="font-size:1.75rem; font-weight:800; line-height:1;"><?php echo $prediction; ?></div>
                        <div style="font-size:0.8rem; opacity:0.85; font-weight:500; margin-top:0.25rem;">Placement Prediction</div>
                    </div>
                </div>
            </div>

            <!-- AI Insights Row -->
            <div class="row g-3 mb-4">
                <!-- Job Recommendations -->
                <div class="col-md-6">
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden;">
                        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between;">
                            <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-lightbulb me-2" style="color:#f59e0b;"></i>Job Recommendations</h4>
                        </div>
                        <div style="padding:1.5rem;">
                            <?php if (count($recommendations) > 0): ?>
                                <?php foreach ($recommendations as $rec): ?>
                                    <div style="display:flex; align-items:center; gap:0.75rem; padding:0.6rem 0; border-bottom:1px solid #f1f5f9;">
                                        <div style="width:32px; height:32px; border-radius:8px; background:#eef2ff; color:#6366f1; display:flex; align-items:center; justify-content:center; font-size:0.8rem; flex-shrink:0;"><i class="fas fa-briefcase"></i></div>
                                        <span style="font-size:0.9rem; color:#334155; font-weight:500;"><?php echo htmlspecialchars($rec); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p style="color:#94a3b8; font-size:0.9rem; margin:0; text-align:center; padding:1rem 0;">Complete your profile to get recommendations</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Skill Gap -->
                <div class="col-md-6">
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden;">
                        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between;">
                            <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-exclamation-triangle me-2" style="color:#f59e0b;"></i>Skill Gap Analysis</h4>
                        </div>
                        <div style="padding:1.5rem;">
                            <?php if (count($missingSkills) > 0): ?>
                                <div style="display:flex; flex-wrap:wrap; gap:0.5rem;">
                                    <?php foreach ($missingSkills as $skill): ?>
                                        <span style="background:rgba(239,68,68,0.1); color:#ef4444; padding:0.3rem 0.8rem; border-radius:9999px; font-size:0.8rem; font-weight:600;"><?php echo htmlspecialchars(trim($skill)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p style="color:#10b981; font-size:0.9rem; margin:0; text-align:center; padding:1rem 0;"><i class="fas fa-check-circle me-1"></i> You're all caught up! No skill gaps detected.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Notifications -->
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between;">
                    <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-bell me-2" style="color:#6366f1;"></i>Recent Notifications</h4>
                    <a href="notifications.php" style="color:#4f46e5; font-size:0.85rem; font-weight:600; text-decoration:none;">View All →</a>
                </div>
                <div>
                    <?php
                    $notifs = $conn->query("SELECT * FROM notifications WHERE student_id='$student_id' OR student_id IS NULL ORDER BY created_at DESC LIMIT 5");
                    if ($notifs->num_rows > 0) {
                        while ($notif = $notifs->fetch_assoc()) {
                            $iconBg = 'rgba(99,102,241,0.1)'; $iconColor = '#6366f1'; $icon = 'fa-info-circle';
                            if ($notif['type'] == 'status_update') { $iconBg = 'rgba(59,130,246,0.1)'; $iconColor = '#3b82f6'; $icon = 'fa-sync-alt'; }
                            elseif ($notif['type'] == 'interview') { $iconBg = 'rgba(245,158,11,0.1)'; $iconColor = '#f59e0b'; $icon = 'fa-calendar-check'; }
                            echo '<div style="display:flex; gap:1rem; padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                                <div style="width:40px; height:40px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; background:'.$iconBg.'; color:'.$iconColor.';"><i class="fas '.$icon.'"></i></div>
                                <div style="flex:1; min-width:0;">
                                    <p style="margin:0; font-size:0.9rem; color:#334155; line-height:1.5;">'.htmlspecialchars($notif['message']).'</p>
                                    <div style="font-size:0.8rem; color:#94a3b8; margin-top:0.25rem;">'.date('d M Y, h:i A', strtotime($notif['created_at'])).'</div>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<div style="padding:2rem; text-align:center; color:#94a3b8;">
                            <i class="fas fa-bell-slash" style="font-size:2rem; margin-bottom:0.5rem; display:block; opacity:0.5;"></i>
                            <p style="margin:0; font-size:0.9rem;">No notifications yet</p>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
</body>
</html>