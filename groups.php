<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$user_id = $_SESSION['user_id'];

// Create new group
if (isset($_POST['create_group'])) {
    $group_name = $_POST['group_name'];
    $monthly_contribution = $_POST['monthly_contribution'];
    
    $sql = "INSERT INTO groups (group_name, created_by, monthly_contribution) VALUES ('$group_name', $user_id, $monthly_contribution)";
    if ($conn->query($sql)) {
        $group_id = $conn->insert_id;
        // Add creator as member
        $conn->query("INSERT INTO group_members (group_id, user_id) VALUES ($group_id, $user_id)");
        $success = "Group created successfully!";
    } else {
        $error = "Error creating group";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SaveSmart ROSCA - Groups</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <h1>SaveSmart ROSCA</h1>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Group Management</h2>
        
        <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

        <!-- Create Group Form -->
        <div class="form-section">
            <h3>Create New Group</h3>
            <form method="POST">
                <input type="text" name="group_name" placeholder="Group Name" required>
                <input type="number" name="monthly_contribution" placeholder="Monthly Contribution (TZS)" required>
                <button type="submit" name="create_group">Create Group</button>
            </form>
        </div>

        <!-- My Groups Table -->
        <h3>My Groups</h3>
        <table>
            <tr>
                <th>Group Name</th>
                <th>Monthly Contribution</th>
                <th>Total Members</th>
                <th>Created Date</th>
            </tr>
            <?php
            $sql = "SELECT g.* FROM groups g 
                    JOIN group_members gm ON g.group_id = gm.group_id 
                    WHERE gm.user_id = $user_id";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row['group_name']."</td>
                            <td>TZS ".number_format($row['monthly_contribution'])."</td>
                            <td>".$row['total_members']."</td>
                            <td>".$row['created_date']."</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>You have not joined any group yet.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>