<?php include("config/db.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NextGen Placement Cell Management System — Smart placement management for students, companies, and admins.">
    <title>NextGen — Placement Cell Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 50%, #f8fafc 100%); -webkit-font-smoothing: antialiased; }
    </style>
</head>
<body>

<?php include("includes/navbar.php"); ?>

<!-- Hero Section -->
<section style="min-height: calc(100vh - 70px); display: flex; align-items: center; padding: 4rem 0; position: relative; overflow: hidden;">
    <div style="position:absolute; top:-50%; right:-20%; width:600px; height:600px; background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, transparent 70%); border-radius:50%;"></div>
    <div style="position:absolute; bottom:-30%; left:-10%; width:400px; height:400px; background: radial-gradient(circle, rgba(16,185,129,0.06) 0%, transparent 70%); border-radius:50%;"></div>
    <div class="container" style="position:relative; z-index:1;">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <div style="display:inline-flex; align-items:center; gap:0.5rem; background:#eef2ff; color:#4338ca; border:1px solid #c7d2fe; padding:0.4rem 1rem; border-radius:9999px; font-size:0.8rem; font-weight:600; letter-spacing:0.02em; margin-bottom:1.5rem;">
                    <span style="width:6px; height:6px; border-radius:50%; background:#6366f1; display:inline-block;"></span>
                    Next-Gen Placement Portal
                </div>
                <h1 style="font-size: clamp(2.2rem, 4vw, 3.5rem); font-weight: 900; line-height: 1.15; color: #0f172a; letter-spacing: -0.03em; margin-bottom: 1.25rem;">
                    Smart placement management for 
                    <span style="background: linear-gradient(135deg, #6366f1, #8b5cf6, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">students, companies & admins.</span>
                </h1>
                <p style="font-size: 1.15rem; color: #64748b; max-width: 520px; margin-bottom: 2rem;">
                    Streamline hiring workflows with a premium dashboard experience, intelligent candidate matching, and real-time notifications.
                </p>
                <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                    <a href="login.php" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 1.75rem; border-radius:9999px; font-weight:600; font-size:0.95rem; background:linear-gradient(135deg, #6366f1, #8b5cf6, #a78bfa); color:#fff; text-decoration:none; transition:all 250ms; border:none;">
                        <i class="fas fa-sign-in-alt"></i> Login to Dashboard
                    </a>
                    <a href="register.php" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 1.75rem; border-radius:9999px; font-weight:600; font-size:0.95rem; background:transparent; color:#4f46e5; text-decoration:none; border:2px solid #c7d2fe; transition:all 250ms;">
                        <i class="fas fa-user-plus"></i> Create Account
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 30%, #4338ca 70%, #6366f1 100%); border-radius: 24px; color: #fff; padding: 2.5rem; position: relative; overflow: hidden;">
                    <div style="position:absolute; top:0; right:0; width:200px; height:200px; background:radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius:50%;"></div>
                    <div style="position:relative; z-index:1;">
                        <h2 style="font-weight:700; font-size:1.5rem; margin-bottom:1rem; color:#fff;">
                            <i class="fas fa-rocket me-2" style="opacity:0.8;"></i>Explore next-gen placement tools
                        </h2>
                        <p style="color: rgba(255,255,255,0.75); font-size: 0.95rem; line-height: 1.7; margin-bottom:1.5rem;">
                            Modern UI, clean workflows, and powerful analytics — built for the modern campus.
                        </p>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 d-flex align-items-center gap-2" style="color:rgba(255,255,255,0.9);">
                                <i class="fas fa-check-circle" style="color:#34d399;"></i>
                                Responsive dashboards with smooth interactions
                            </li>
                            <li class="mb-3 d-flex align-items-center gap-2" style="color:rgba(255,255,255,0.9);">
                                <i class="fas fa-check-circle" style="color:#34d399;"></i>
                                Personalized student recommendations
                            </li>
                            <li class="d-flex align-items-center gap-2" style="color:rgba(255,255,255,0.9);">
                                <i class="fas fa-check-circle" style="color:#34d399;"></i>
                                Real-time notification & interview tracking
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="container" style="padding: 5rem 0;">
    <div class="text-center mb-5">
        <div style="display:inline-flex; align-items:center; gap:0.5rem; background:#eef2ff; color:#4338ca; border:1px solid #c7d2fe; padding:0.4rem 1rem; border-radius:9999px; font-size:0.8rem; font-weight:600; margin-bottom:0.75rem;">
            <span style="width:6px; height:6px; border-radius:50%; background:#6366f1; display:inline-block;"></span>
            Why NextGen?
        </div>
        <h2 style="font-size: 2.2rem; font-weight: 900; line-height: 1.15; color: #0f172a; max-width: 600px; margin: 0.75rem auto 0;">
            Everything you need for <span style="background: linear-gradient(135deg, #6366f1, #8b5cf6, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">campus placements</span>
        </h2>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:2rem; transition:all 250ms; box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06);">
                <div style="width:56px; height:56px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; margin-bottom:1.25rem; background:#eef2ff; color:#4f46e5;">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h5 style="font-size:1.05rem; font-weight:700; margin-bottom:0.5rem;">Student Portal</h5>
                <p style="color:#64748b; font-size:0.9rem; margin:0; line-height:1.6;">Build your profile, upload resumes, browse jobs, apply with one click, and track your application status in real-time.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:2rem; transition:all 250ms; box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06);">
                <div style="width:56px; height:56px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; margin-bottom:1.25rem; background:rgba(16,185,129,0.1); color:#059669;">
                    <i class="fas fa-building"></i>
                </div>
                <h5 style="font-size:1.05rem; font-weight:700; margin-bottom:0.5rem;">Company Portal</h5>
                <p style="color:#64748b; font-size:0.9rem; margin:0; line-height:1.6;">Post job openings, view applicants with AI-powered resume scoring, shortlist candidates, and manage the hiring pipeline.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:2rem; transition:all 250ms; box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06);">
                <div style="width:56px; height:56px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; margin-bottom:1.25rem; background:rgba(245,158,11,0.1); color:#f59e0b;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h5 style="font-size:1.05rem; font-weight:700; margin-bottom:0.5rem;">Admin Dashboard</h5>
                <p style="color:#64748b; font-size:0.9rem; margin:0; line-height:1.6;">Oversee all placement operations, manage users, generate reports, post notifications, and track year-wise placement statistics.</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 30%, #4338ca 70%, #6366f1 100%); padding: 4rem 0;">
    <div class="container">
        <div class="row text-center text-white gy-4">
            <?php
            $totalStudents = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];
            $totalCompanies = $conn->query("SELECT COUNT(*) AS total FROM companies")->fetch_assoc()['total'];
            $totalJobs = $conn->query("SELECT COUNT(*) AS total FROM jobs")->fetch_assoc()['total'];
            $totalPlaced = $conn->query("SELECT COUNT(*) AS total FROM placements")->fetch_assoc()['total'];
            ?>
            <div class="col-6 col-md-3">
                <h2 class="fw-bold mb-1" style="font-size: 2.5rem; color: #fff;"><?php echo $totalStudents; ?></h2>
                <p style="color: rgba(255,255,255,0.7); margin: 0; font-weight: 500;">Students Registered</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="fw-bold mb-1" style="font-size: 2.5rem; color: #fff;"><?php echo $totalCompanies; ?></h2>
                <p style="color: rgba(255,255,255,0.7); margin: 0; font-weight: 500;">Companies</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="fw-bold mb-1" style="font-size: 2.5rem; color: #fff;"><?php echo $totalJobs; ?></h2>
                <p style="color: rgba(255,255,255,0.7); margin: 0; font-weight: 500;">Jobs Posted</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="fw-bold mb-1" style="font-size: 2.5rem; color: #fff;"><?php echo $totalPlaced; ?></h2>
                <p style="color: rgba(255,255,255,0.7); margin: 0; font-weight: 500;">Students Placed</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="container" style="padding: 5rem 0; text-center;">
    <div class="text-center">
        <h2 style="font-weight: 800; font-size: 2rem; margin-bottom: 1rem; color: #0f172a;">Ready to get started?</h2>
        <p style="color: #64748b; max-width: 500px; margin: 0 auto 2rem; font-size: 1.05rem;">
            Join our placement platform and connect with top companies and talented students.
        </p>
        <a href="register.php" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.85rem 2rem; border-radius:9999px; font-weight:600; font-size:1rem; background:linear-gradient(135deg, #6366f1, #8b5cf6, #a78bfa); color:#fff; text-decoration:none; border:none;">
            <i class="fas fa-arrow-right"></i> Get Started Free
        </a>
    </div>
</section>

<?php include("includes/footer.php"); ?>
</body>
</html>