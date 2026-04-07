<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$sidebarLinks = [
    ['url' => 'company_dashboard.php', 'icon' => 'fa-th-large', 'label' => 'Dashboard'],
    ['url' => 'post_job.php', 'icon' => 'fa-plus-circle', 'label' => 'Post Job'],
    ['url' => 'view_applicants.php', 'icon' => 'fa-users', 'label' => 'View Applicants'],
    ['url' => 'shortlist_students.php', 'icon' => 'fa-user-check', 'label' => 'Shortlist Students'],
];
?>
<div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.06); overflow:hidden; position:sticky; top:90px;">
    <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; font-weight:700; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.08em; color:#94a3b8;">Company Menu</div>
    <?php foreach ($sidebarLinks as $link): 
        $isActive = ($currentPage === $link['url']);
        $style = $isActive 
            ? "display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1.5rem; color:#4338ca; font-weight:600; font-size:0.9rem; text-decoration:none; border-left:3px solid #4f46e5; background:#eef2ff;"
            : "display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1.5rem; color:#475569; font-weight:500; font-size:0.9rem; text-decoration:none; border-left:3px solid transparent;";
        $iconOpacity = $isActive ? '' : 'opacity:0.7;';
    ?>
        <a href="<?php echo $link['url']; ?>" style="<?php echo $style; ?>"><i class="fas <?php echo $link['icon']; ?>" style="width:20px; text-align:center; <?php echo $iconOpacity; ?>"></i> <?php echo $link['label']; ?></a>
    <?php endforeach; ?>
    <div style="height:1px; background:#f1f5f9; margin:0.5rem 0;"></div>
    <a href="../logout.php" style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1.5rem; color:#ef4444; font-weight:500; font-size:0.9rem; text-decoration:none; border-left:3px solid transparent;"><i class="fas fa-sign-out-alt" style="width:20px; text-align:center; opacity:0.7;"></i> Logout</a>
</div>
