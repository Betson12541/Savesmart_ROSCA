<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$user_id = $_SESSION['user_id'];

// Apply for loan
if (isset($_POST['apply_loan'])) {
    $group_id = $_POST['group_id'];
    $amount = $_POST['amount'];
    $reason = $_POST['reason'];
    
    $sql = "INSERT INTO loans (user_id, group_id, amount, reason) VALUES ($user_id, $group_id, $amount, '$reason')";
    if ($conn->query($sql)) {
        $success = "Loan application submitted! Wait for treasurer approval.";
    } else {
        $error = "Error submitting application";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SaveSmart ROSCA - Loans</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <h1>SaveSmart ROSCA</h1>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Loan Management</h2>
        
        <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

        <!-- Apply Loan Form -->
        <div class="form-section">
            <h3>Apply for Loan</h3>
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
                <input type="number" name="amount" placeholder="Loan Amount (TZS)" required>
                <textarea name="reason" placeholder="Reason for Loan" required></textarea>
                <button type="submit" name="apply_loan">Submit Application</button>
            </form>
        </div>

        <!-- Loan Status -->
        <h3>My Loan Applications</h3>
        <table>
            <tr>
                <th>Date</th>
                <th>Group Name</th>
                <th>Amount</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
            <?php
            $sql = "SELECT l.*, g.group_name FROM loans l 
                    JOIN groups g ON l.group_id = g.group_id 
                    WHERE l.user_id = $user_id 
                    ORDER BY l.application_date DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $status_color = $row['status'] == 'approved' ? 'green' : ($row['status'] == 'rejected' ? 'red' : 'orange');
                    echo "<tr>
                            <td>".$row['application_date']."</td>
                            <td>".$row['group_name']."</td>
                            <td>TZS ".number_format($row['amount'])."</td>
                            <td>".$row['reason']."</td>
                            <td style='color:".$status_color."'>".ucfirst($row['status'])."</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No loan applications yet.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>