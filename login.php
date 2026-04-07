<?php include("config/db.php"); ?>
<?php
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 1. Check Students
    $stmt = $conn->prepare("SELECT * FROM students WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $student = $res->fetch_assoc();
        // Validation with fallback for old MD5 hashes
        if (password_verify($password, $student['password']) || $student['password'] === md5($password)) {
            $_SESSION['student_id'] = $student['student_id'];
            header("Location: student/student_dashboard.php");
            exit();
        }
    }
    $stmt->close();

    // 2. Check Companies
    $stmt = $conn->prepare("SELECT * FROM companies WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $company = $res->fetch_assoc();
        if (password_verify($password, $company['password']) || $company['password'] === md5($password)) {
            $_SESSION['company_id'] = $company['company_id'];
            $_SESSION['company_name'] = $company['name'];
            header("Location: company/company_dashboard.php");
            exit();
        }
    }
    $stmt->close();

    // 3. Check Admins
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $admin = $res->fetch_assoc();
        if (password_verify($password, $admin['password']) || $admin['password'] === md5($password)) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            header("Location: admin/admin_dashboard.php");
            exit();
        }
    }
    $stmt->close();

    $loginError = "Invalid email or password. Please try again.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — NextGen Placement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php include("includes/navbar.php"); ?>

<div class="auth-page">
    <div class="container">
        <div class="row g-0 justify-content-center">
            <div class="col-lg-10">
                <div class="row g-0 shadow-lg" style="border-radius: var(--radius-xl); overflow: hidden; min-height: 580px;">
                    <!-- Left Visual Panel -->
                    <div class="col-lg-5 d-none d-lg-flex" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 30%, #4338ca 70%, #6366f1 100%); padding: 3rem; flex-direction: column; justify-content: center; color: #fff; position: relative; overflow: hidden;">
                        <div style="position:absolute; top:-50%; right:-50%; width:400px; height:400px; background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%); border-radius:50%;"></div>
                        <div style="position: relative; z-index: 1;">
                            <div style="font-size: 2.5rem; margin-bottom: 1.5rem;"><i class="fas fa-graduation-cap"></i></div>
                            <h2 style="color:#fff; font-weight:800; font-size:1.9rem; margin-bottom:1rem;">Welcome back to<br>NextGen Placement</h2>
                            <p style="color: rgba(255,255,255,0.75); font-size: 0.95rem; line-height: 1.7;">Access your personalized dashboard, track applications, manage job postings, and stay updated with real-time notifications.</p>
                            <ul style="list-style:none; padding:0; margin-top:2rem;">
                                <li style="display:flex; align-items:center; gap:0.75rem; padding:0.5rem 0; color:rgba(255,255,255,0.85); font-size:0.9rem;"><i class="fas fa-check-circle" style="color:var(--accent-400);"></i> AI-powered resume scoring</li>
                                <li style="display:flex; align-items:center; gap:0.75rem; padding:0.5rem 0; color:rgba(255,255,255,0.85); font-size:0.9rem;"><i class="fas fa-check-circle" style="color:var(--accent-400);"></i> Real-time application tracking</li>
                                <li style="display:flex; align-items:center; gap:0.75rem; padding:0.5rem 0; color:rgba(255,255,255,0.85); font-size:0.9rem;"><i class="fas fa-check-circle" style="color:var(--accent-400);"></i> Smart job recommendations</li>
                                <li style="display:flex; align-items:center; gap:0.75rem; padding:0.5rem 0; color:rgba(255,255,255,0.85); font-size:0.9rem;"><i class="fas fa-check-circle" style="color:var(--accent-400);"></i> Instant notifications</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Right Form Panel -->
                    <div class="col-lg-7" style="background: #fff; padding: 3rem; display:flex; flex-direction:column; justify-content:center;">
                        <h2 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 0.5rem;">Sign In</h2>
                        <p style="color: var(--gray-500); margin-bottom: 2rem; font-size: 0.95rem;">Enter your credentials to access your account</p>

                        <?php if (isset($loginError)): ?>
                            <div class="alert alert-danger d-flex align-items-center gap-2 py-2" role="alert" style="border-radius: var(--radius-sm); font-size: 0.9rem;">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $loginError; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:600; font-size:0.85rem; color:var(--gray-700);">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: var(--gray-50); border-color: var(--gray-200); color: var(--gray-400);"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="you@example.com" required style="border-color: var(--gray-200); padding: 0.7rem 1rem;">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" style="font-weight:600; font-size:0.85rem; color:var(--gray-700);">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: var(--gray-50); border-color: var(--gray-200); color: var(--gray-400);"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required style="border-color: var(--gray-200); padding: 0.7rem 1rem;">
                                </div>
                            </div>
                            <button type="submit" name="login" class="btn w-100" style="background: linear-gradient(135deg, #6366f1, #8b5cf6, #a78bfa); color: #fff; border:none; border-radius: 9999px; padding: 0.75rem; font-weight: 600; font-size: 0.95rem; cursor:pointer;">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p style="color: var(--gray-500); font-size: 0.9rem;">
                                Don't have an account? <a href="register.php" style="color: var(--primary-600); font-weight: 600;">Create one</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
</body>
</html>