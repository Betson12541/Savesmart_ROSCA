<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$user_id = $_SESSION['user_id'];

// Add contribution
if (isset($_POST['add_contribution'])) {
    $group_id = $_POST['group_id'];
    $amount = $_POST['amount'];
    
    $sql = "INSERT INTO contributions (user_id, group_id, amount) VALUES ($user_id, $group_id, $amount)";
    if ($conn->query($sql)) {
        $success = "Contribution added successfully!";
    } else {
        $error = "Error adding contribution";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SaveSmart ROSCA - Contributions</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <h1>SaveSmart ROSCA</h1>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Contribution Management</h2>
        
        <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

        <!-- Add Contribution Form -->
        <div class="form-section">
            <h3>Add New Contribution</h3>
            <form method="POST">
                <select name="group_id" required>
                    <option value="">Select Group</option>
                    <?php
                    $groups_sql = "SELECT g.* FROM groups g 
                                   JOIN group_members gm ON g.group_id = gm.group_id 
                                   WHERE gm.user_id = $user_id";
                    $groups_result = $conn->query($groups_sql);
                    while($group = $groups_result->fetch_assoc()) {
                        echo "<option value='".$group['group_id']."'>".$group['group_name']."</option>";
                    }
                    ?>
                </select>
                <input type="number" name="amount" placeholder="Amount (TZS)" required>
                <button type="submit" name="add_contribution">Add Contribution</button>
            </form>
        </div>

        <!-- Contribution History -->
        <h3>My Contribution History</h3>
        <table>
            <tr>
                <th>Date</th>
                <th>Group Name</th>
                <th>Amount</th>
            </tr>
            <?php
            $sql = "SELECT c.*, g.group_name FROM contributions c 
                    JOIN groups g ON c.group_id = g.group_id 
                    WHERE c.user_id = $user_id 
                    ORDER BY c.contribution_date DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row['contribution_date']."</td>
                            <td>".$row['group_name']."</td>
                            <td>TZS ".number_format($row['amount'])."</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No contributions yet.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>