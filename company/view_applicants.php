<?php include("../config/db.php"); ?>
<?php
if (!isset($_SESSION['company_id'])) { header("Location: ../login.php"); exit(); }
$company_id = $_SESSION['company_id'];

// Handle shortlisting
if (isset($_GET['shortlist_id'])) {
    $application_id = $_GET['shortlist_id'];
    $check = $conn->query("SELECT applications.application_id FROM applications JOIN jobs ON applications.job_id = jobs.job_id WHERE applications.application_id='$application_id' AND jobs.company_id='$company_id'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE applications SET status='Shortlisted' WHERE application_id='$application_id'");
        $app = $conn->query("SELECT student_id, job_id FROM applications WHERE application_id='$application_id'")->fetch_assoc();
        $job = $conn->query("SELECT title FROM jobs WHERE job_id='".$app['job_id']."'")->fetch_assoc();
        $message = "Congratulations! You have been shortlisted for the job: ".$job['title'];
        $conn->query("INSERT INTO notifications (student_id, message, type) VALUES ('".$app['student_id']."', '$message', 'status_update')");
        header("Location: view_applicants.php?msg=shortlisted"); exit();
    }
}

// Handle selection
if (isset($_GET['select_id'])) {
    $application_id = $_GET['select_id'];
    $check = $conn->query("SELECT applications.application_id FROM applications JOIN jobs ON applications.job_id = jobs.job_id WHERE applications.application_id='$application_id' AND jobs.company_id='$company_id'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE applications SET status='Selected' WHERE application_id='$application_id'");
        $app = $conn->query("SELECT student_id, job_id FROM applications WHERE application_id='$application_id'")->fetch_assoc();
        $job = $conn->query("SELECT title FROM jobs WHERE job_id='".$app['job_id']."'")->fetch_assoc();
        $message = "Congratulations! You have been selected for the job: ".$job['title'];
        $conn->query("INSERT INTO notifications (student_id, message, type) VALUES ('".$app['student_id']."', '$message', 'status_update')");
        $conn->query("INSERT INTO placements (student_id, job_id, company_id) VALUES ('".$app['student_id']."', '".$app['job_id']."', '$company_id')");
        header("Location: view_applicants.php?msg=selected"); exit();
    }
}

// Handle rejection
if (isset($_GET['reject_id'])) {
    $application_id = $_GET['reject_id'];
    $check = $conn->query("SELECT applications.application_id FROM applications JOIN jobs ON applications.job_id = jobs.job_id WHERE applications.application_id='$application_id' AND jobs.company_id='$company_id'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE applications SET status='Rejected' WHERE application_id='$application_id'");
        $app = $conn->query("SELECT student_id, job_id FROM applications WHERE application_id='$application_id'")->fetch_assoc();
        $job = $conn->query("SELECT title FROM jobs WHERE job_id='" . $app['job_id'] . "'")->fetch_assoc();
        $message = "Unfortunately, you have been rejected for the job: " . $job['title'];
        $conn->query("INSERT INTO notifications (student_id, message, type) VALUES ('" . $app['student_id'] . "', '$message', 'status_update')");
        header("Location: view_applicants.php?msg=rejected"); exit();
    }
}

function normalize_keywords($text) {
    $text = mb_strtolower($text, 'UTF-8');
    $text = str_replace(["\n", "\r", "\t"], ' ', $text);
    $text = preg_replace('/[^\p{L}\p{N}\+\#]+/u', ' ', $text);
    $tokens = preg_split('/[\s,]+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
    $stopwords = ['and','or','with','for','the','a','an','to','of','in','on','at','by','from','as','is','are','will','has','have','be','this','that','these','those','your','you','candidate','job','role','responsibilities','requirements','requirement','experience','skills','skill','student','company','apply','relevant','seeking'];
    $keywords = [];
    foreach ($tokens as $token) {
        $token = trim($token);
        if ($token === '' || in_array($token, $stopwords, true) || mb_strlen($token, 'UTF-8') <= 1) continue;
        $keywords[$token] = true;
    }
    return array_keys($keywords);
}

function calculate_resume_score($student, $job) {
    $jobText = trim($job['title'] . ' ' . $job['required_skills'] . ' ' . $job['description']);
    $jobKeywords = normalize_keywords($jobText);
    $profileText = trim($student['skills'] . ' ' . $student['projects'] . ' ' . $student['education'] . ' ' . $student['experience']);
    $profileKeywords = normalize_keywords($profileText);
    $skillKeywords = normalize_keywords($student['skills']);
    $matchedKeywords = array_intersect($jobKeywords, $profileKeywords);
    $matchedSkills = array_intersect($skillKeywords, $jobKeywords);
    $jobCount = count($jobKeywords); $matchedCount = count($matchedKeywords);
    $keywordRelevance = $jobCount ? ($matchedCount / $jobCount) : 0;
    $skillPrecision = count($skillKeywords) ? (count($matchedSkills) / count($skillKeywords)) : 0;
    $profileRelevance = count($profileKeywords) ? ($matchedCount / count($profileKeywords)) : 0;
    $baseScore = ($keywordRelevance * 0.60 + $skillPrecision * 0.30 + $profileRelevance * 0.10) * 100;
    $resumeBonus = !empty($student['resume']) ? 8 : 0;
    return ['score' => min(100, round($baseScore + $resumeBonus)), 'matchedKeywords' => array_values($matchedKeywords), 'matchedCount' => $matchedCount, 'jobCount' => $jobCount];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicants — NextGen</title>
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
            <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:0.25rem;">Applicants</h2>
            <p style="color:#64748b; font-size:0.9rem; margin-bottom:1.5rem;">Review and manage job applications</p>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success d-flex align-items-center gap-2" style="border-radius:12px; font-size:0.9rem;"><i class="fas fa-check-circle"></i> Student <?php echo htmlspecialchars($_GET['msg']); ?> successfully!</div>
            <?php endif; ?>

            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                    <h4 style="margin:0; font-size:1.05rem; font-weight:700;"><i class="fas fa-users me-2" style="color:#6366f1;"></i>Pending Applications</h4>
                </div>
                <div class="table-responsive">
                    <table style="border-collapse:separate; border-spacing:0; width:100%;">
                        <thead>
                            <tr>
                                <?php foreach(['Job','Student','CGPA','Resume Score','Resume','Actions'] as $h): ?>
                                <th style="background:#1e293b; color:#fff; font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1rem; border:none; white-space:nowrap;"><?php echo $h; ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = $conn->query("SELECT applications.*, jobs.title, jobs.required_skills, jobs.cgpa_requirement, jobs.description, students.name, students.email, students.cgpa, students.skills, students.resume, students.experience, students.education, students.projects FROM applications JOIN jobs ON applications.job_id = jobs.job_id JOIN students ON applications.student_id = students.student_id WHERE jobs.company_id='$company_id' AND applications.status = 'Applied' ORDER BY applications.applied_at DESC");
                            if ($res->num_rows > 0) {
                                while ($app = $res->fetch_assoc()) {
                                    $scoreData = calculate_resume_score($app, $app);
                                    $scorePct = $scoreData['score'];
                                    $scoreColor = $scorePct >= 70 ? '#10b981' : ($scorePct >= 40 ? '#f59e0b' : '#ef4444');
                                    echo '<tr>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9;"><strong style="font-size:0.9rem; color:#334155;">'.htmlspecialchars($app['title']).'</strong></td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9;"><div style="font-size:0.9rem; font-weight:600; color:#334155;">'.htmlspecialchars($app['name']).'</div><div style="font-size:0.8rem; color:#94a3b8;">'.htmlspecialchars($app['email']).'</div></td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.9rem; color:#475569;">'.$app['cgpa'].'</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9;">
                                            <div style="font-size:1.1rem; font-weight:800; color:'.$scoreColor.';">'.$scorePct.'%</div>
                                            <div style="width:60px; height:4px; border-radius:9999px; background:#e2e8f0; margin-top:0.25rem;"><div style="width:'.$scorePct.'%; height:100%; border-radius:9999px; background:'.$scoreColor.';"></div></div>
                                            <div style="font-size:0.75rem; color:#94a3b8; margin-top:0.15rem;">'.$scoreData['matchedCount'].'/'.$scoreData['jobCount'].' keywords</div>
                                        </td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9;">';
                                    if (!empty($app['resume'])) {
                                        echo '<a href="'.$app['resume'].'" target="_blank" style="display:inline-flex; align-items:center; gap:0.25rem; padding:0.3rem 0.75rem; border-radius:8px; font-size:0.8rem; font-weight:600; background:rgba(59,130,246,0.1); color:#3b82f6; text-decoration:none;"><i class="fas fa-file-pdf"></i> View</a>';
                                    } else {
                                        echo '<span style="font-size:0.8rem; color:#94a3b8;">None</span>';
                                    }
                                    echo '</td>
                                        <td style="padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9;">
                                            <div style="display:flex; gap:0.35rem; flex-wrap:wrap;">
                                                <a href="view_applicants.php?shortlist_id='.$app['application_id'].'" style="width:30px; height:30px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; border:none; font-size:0.8rem; background:rgba(59,130,246,0.1); color:#3b82f6; text-decoration:none;" title="Shortlist"><i class="fas fa-list"></i></a>
                                                <a href="view_applicants.php?select_id='.$app['application_id'].'" style="width:30px; height:30px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; border:none; font-size:0.8rem; background:rgba(16,185,129,0.1); color:#10b981; text-decoration:none;" title="Select"><i class="fas fa-check"></i></a>
                                                <a href="view_applicants.php?reject_id='.$app['application_id'].'" onclick="return confirm(\'Are you sure?\')" style="width:30px; height:30px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; border:none; font-size:0.8rem; background:rgba(239,68,68,0.1); color:#ef4444; text-decoration:none;" title="Reject"><i class="fas fa-times"></i></a>
                                            </div>
                                        </td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="6" style="padding:2rem; text-align:center; color:#94a3b8;"><i class="fas fa-inbox" style="font-size:2rem; display:block; margin-bottom:0.5rem; opacity:0.5;"></i>No pending applications</td></tr>';
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