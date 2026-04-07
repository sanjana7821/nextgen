<?php
// Detect if we're in a subdirectory (admin, student, company) or root
$isSubdir = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false 
          || strpos($_SERVER['SCRIPT_NAME'], '/student/') !== false 
          || strpos($_SERVER['SCRIPT_NAME'], '/company/') !== false);
$basePath = $isSubdir ? '../' : '';
?>
<nav class="navbar navbar-expand-lg navbar-premium fixed-top">
  <div class="container">
    <a class="navbar-brand" href="<?php echo $basePath; ?>index.php">
      <i class="fas fa-graduation-cap me-2"></i>NextGen
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon-custom"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
        <?php if (isset($_SESSION['student_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo $basePath; ?>student/student_dashboard.php"><i class="fas fa-th-large me-1"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $basePath; ?>student/view_jobs.php"><i class="fas fa-briefcase me-1"></i> Jobs</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $basePath; ?>student/notifications.php"><i class="fas fa-bell me-1"></i> Notifications</a></li>
          <li class="nav-item ms-lg-2"><a class="nav-link btn-nav-logout" href="<?php echo $basePath; ?>logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
        <?php elseif (isset($_SESSION['company_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo $basePath; ?>company/company_dashboard.php"><i class="fas fa-th-large me-1"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $basePath; ?>company/post_job.php"><i class="fas fa-plus-circle me-1"></i> Post Job</a></li>
          <li class="nav-item ms-lg-2"><a class="nav-link btn-nav-logout" href="<?php echo $basePath; ?>logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
        <?php elseif (isset($_SESSION['admin_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo $basePath; ?>admin/admin_dashboard.php"><i class="fas fa-th-large me-1"></i> Dashboard</a></li>
          <li class="nav-item ms-lg-2"><a class="nav-link btn-nav-logout" href="<?php echo $basePath; ?>logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo $basePath; ?>index.php">Home</a></li>
          <li class="nav-item ms-lg-1"><a class="nav-link btn-nav-login" href="<?php echo $basePath; ?>login.php">Login</a></li>
          <li class="nav-item ms-lg-1"><a class="nav-link btn-nav-register" href="<?php echo $basePath; ?>register.php">Get Started</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<!-- Spacer for fixed navbar -->
<div style="height: 70px;"></div>