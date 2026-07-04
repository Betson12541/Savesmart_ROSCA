<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';
$user_id = $_SESSION['user_id'];

// count user groups  
$groups_sql = "SELECT COUNT(*) as total FROM group_members WHERE user_id = $user_id";
$groups_count = $conn->query($groups_sql)->fetch_assoc()['total'];

// count total contributions
$contrib_sql = "SELECT SUM(amount) as total FROM contributions WHERE user_id = $user_id";
$contrib_total = $conn->query($contrib_sql)->fetch_assoc()['total'] ?? 0;


$loans_sql = "SELECT COUNT(*) as total FROM loans WHERE user_id = $user_id AND status = 'Pending'";
$loans_count = $conn->query($loans_sql)->fetch_assoc()['total'];
?>

<html>
<head>
    <title>SaveSmart ROSCA - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <h1>SaveSmart ROSCA</h1>
        <a href="dashboard.php">Dashboard</a>
        <a href="groups.php">Create/Join Group</a>
        <a href="contributions.php">Add Contribution</a>
        <a href="loans.php">Apply for Loan</a>
        <a href="logout.php">Logout</a>
    </div>
    
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['full_name']; ?>!</h2>
        
        <div class="dashboard-cards">
            <div class="card">
                <h3>My Groups</h3>
                <p style="font-size: 32px;"><?php echo $groups_count; ?></p>
            </div>
            <div class="card">
                <h3>Total Contributed</h3>
                <p style="font-size: 32px;">TZS <?php echo number_format($contrib_total); ?></p>
            </div>
            <div class="card">
                <h3>Pending Loans</h3>
                <p style="font-size: 32px;"><?php echo $loans_count; ?></p>
            </div>
        </div>
        
        <h3>ROSCA Summary</h3>
        <p>This is your Rotating Savings and Credit Association dashboard. Create or join groups, make monthly contributions, and apply for loans from your group's pooled funds.</p>
    </div>
</body>
</html>
