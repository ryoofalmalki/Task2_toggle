<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task2";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];

    $stmt = $conn->prepare("INSERT INTO task2_users (name, age) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $age);
    $stmt->execute();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toggle'])) {
    $id = $_POST['id'];
    $result = $conn->query("SELECT status FROM task2_users WHERE id = $id");
    $row = $result->fetch_assoc();
    $new_status = $row['status'] == 0 ? 1 : 0;
    $conn->query("UPDATE task2_users SET status = $new_status WHERE id = $id");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task 2 - Smart Methods</title>
    <style>
    body {
        font-family: Arial;
        padding: 20px;
    }

    table {
        border: 1px solid black;
        border-collapse: collapse;
        width: 60%;
        background-color: #e0f7fa; 
    }

    th, td {
        border: 1px solid black;
        padding: 6px;
        text-align: center;
    }

    input[type="text"],
    input[type="number"] {
        padding: 5px;
    }

    button {
        background-color: #e1bee7; 
        border: none;
        padding: 6px 12px;
        cursor: pointer;
    }

    button:hover {
        background-color: #d1c4e9; 
    }
</style>
</head>
<body>

    <h2>🎀 Share Your Cute Details 🎀</h2>
    <form method="post" action="">
        Name: <input type="text" name="name" required>
        Age: <input type="number" name="age" required>
        <button type="submit" name="add">Submit</button>
    </form>

    <h2>💗✨Lovely Users Table💗✨</h2>
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Age</th><th>Status</th><th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM task2_users");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['age']}</td>
                <td>{$row['status']}</td>
                <td>
                    <form method='post' action=''>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <button type='submit' name='toggle'>Toggle</button>
                    </form>
                </td>
            </tr>";
        }
        ?>
    </table>

</body>
</html>
