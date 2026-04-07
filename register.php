<?php include("config/db.php"); ?>
<?php
$registerError = '';
$registerSuccess = false;

if (isset($_POST['register'])) {
    $role = $_POST['role'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if ($role == "student") {
        $stmt = $conn->prepare("SELECT student_id FROM students WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $registerError = "An account with this email already exists.";
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO students (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password);
            if ($stmt->execute()) {
                $registerSuccess = true;
            } else {
                $registerError = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    } elseif ($role == "company") {
        $stmt = $conn->prepare("SELECT company_id FROM companies WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $registerError = "An account with this email already exists.";
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO companies (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password);
            if ($stmt->execute()) {
                $registerSuccess = true;
            } else {
                $registerError = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    } else {
        $registerError = "Invalid role selected.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — NextGen Placement</title>
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
                            <div style="font-size: 2.5rem; margin-bottom: 1.5rem;"><i class="fas fa-rocket"></i></div>
                            <h2 style="color:#fff; font-weight:800; font-size:1.9rem; margin-bottom:1rem;">Join NextGen<br>Placement Portal</h2>
                            <p style="color: rgba(255,255,255,0.75); font-size: 0.95rem; line-height: 1.7;">Create your account and get started with the most modern placement management experience.</p>
                            <ul style="list-style:none; padding:0; margin-top:2rem;">
                                <li style="display:flex; align-items:center; gap:0.75rem; padding:0.5rem 0; color:rgba(255,255,255,0.85); font-size:0.9rem;"><i class="fas fa-check-circle" style="color:var(--accent-400);"></i> Students: Build profiles &amp; apply to jobs</li>
                                <li style="display:flex; align-items:center; gap:0.75rem; padding:0.5rem 0; color:rgba(255,255,255,0.85); font-size:0.9rem;"><i class="fas fa-check-circle" style="color:var(--accent-400);"></i> Companies: Post jobs &amp; hire talent</li>
                                <li style="display:flex; align-items:center; gap:0.75rem; padding:0.5rem 0; color:rgba(255,255,255,0.85); font-size:0.9rem;"><i class="fas fa-check-circle" style="color:var(--accent-400);"></i> AI-powered candidate matching</li>
                                <li style="display:flex; align-items:center; gap:0.75rem; padding:0.5rem 0; color:rgba(255,255,255,0.85); font-size:0.9rem;"><i class="fas fa-check-circle" style="color:var(--accent-400);"></i> Real-time status tracking</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Right Form Panel -->
                    <div class="col-lg-7" style="background: #fff; padding: 3rem; display:flex; flex-direction:column; justify-content:center;">
                        <?php if ($registerSuccess): ?>
                            <div class="text-center py-4">
                                <div style="width:70px; height:70px; border-radius:50%; background: rgba(16,185,129,0.1); display:inline-flex; align-items:center; justify-content:center; margin-bottom:1.5rem;">
                                    <i class="fas fa-check" style="font-size:1.8rem; color:var(--success);"></i>
                                </div>
                                <h2 style="font-size:1.5rem; font-weight:800;">Account Created!</h2>
                                <p style="color:var(--gray-500); margin-bottom:1.5rem;">Your account has been registered successfully.</p>
                                <a href="login.php" class="btn btn-primary" style="background: var(--gradient-primary); border:none; border-radius: var(--radius-full); padding: 0.65rem 2rem; font-weight: 600;">
                                    <i class="fas fa-sign-in-alt me-2"></i>Go to Login
                                </a>
                            </div>
                        <?php else: ?>
                            <h2 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 0.5rem;">Create Account</h2>
                            <p style="color: var(--gray-500); margin-bottom: 1.5rem; font-size: 0.95rem;">Choose your role and fill in your details</p>

                            <?php if (!empty($registerError)): ?>
                                <div class="alert alert-danger d-flex align-items-center gap-2 py-2" role="alert" style="border-radius: var(--radius-sm); font-size: 0.9rem;">
                                    <i class="fas fa-exclamation-circle"></i> <?php echo $registerError; ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST">
                                <!-- Role Selector -->
                                <div class="mb-3">
                                    <label class="form-label" style="font-weight:600; font-size:0.85rem; color:var(--gray-700);">I am a...</label>
                                    <div class="role-selector">
                                        <label class="role-card selected" id="role-student-card" onclick="selectRole('student')">
                                            <i class="fas fa-user-graduate"></i>
                                            <span>Student</span>
                                        </label>
                                        <label class="role-card" id="role-company-card" onclick="selectRole('company')">
                                            <i class="fas fa-building"></i>
                                            <span>Company</span>
                                        </label>
                                    </div>
                                    <input type="hidden" name="role" id="roleInput" value="student">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" id="nameLabel" style="font-weight:600; font-size:0.85rem; color:var(--gray-700);">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="nameIconWrap" style="background: var(--gray-50); border-color: var(--gray-200); color: var(--gray-400);"><i class="fas fa-user" id="nameIcon"></i></span>
                                        <input type="text" name="name" class="form-control" placeholder="Enter your name" required style="border-color: var(--gray-200); padding: 0.7rem 1rem;">
                                    </div>
                                </div>
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
                                        <input type="password" name="password" class="form-control" placeholder="Create a strong password" required minlength="6" style="border-color: var(--gray-200); padding: 0.7rem 1rem;">
                                    </div>
                                </div>
                                <button type="submit" name="register" class="btn btn-primary w-100" style="background: var(--gradient-primary); border:none; border-radius: var(--radius-full); padding: 0.75rem; font-weight: 600; font-size: 0.95rem;">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
                            </form>

                            <div class="text-center mt-4">
                                <p style="color: var(--gray-500); font-size: 0.9rem;">
                                    Already have an account? <a href="login.php" style="color: var(--primary-600); font-weight: 600;">Sign in</a>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectRole(role) {
    document.getElementById('roleInput').value = role;
    document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('role-' + role + '-card').classList.add('selected');
    const nameLabel = document.getElementById('nameLabel');
    const nameIcon = document.getElementById('nameIcon');
    if (role === 'company') {
        nameLabel.textContent = 'Company Name';
        nameIcon.className = 'fas fa-building';
    } else {
        nameLabel.textContent = 'Full Name';
        nameIcon.className = 'fas fa-user';
    }
}
</script>

<?php include("includes/footer.php"); ?>
</body>
</html>