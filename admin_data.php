<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

if (isset($_SESSION['name'])) {  
    $user_name = $_SESSION['name'];
} else {
    $user_name = "No name found"; 
}

$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] :"Not found";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Table</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100">

<div class="container mx-auto mt-10">
    <div class="container mx-auto mt-10">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Welcome, <span class="text-indigo-600"><?php echo htmlspecialchars($user_name); ?></span>!</h2>

            <div class="text-center mb-6">
                <p class="text-2xl font-bold text-gray-500 mb-2">Data Table</p>
                <p class="text-xl text-gray-500">Admin ID: <span class="font-semibold"><?php echo htmlspecialchars($user_id); ?></span></p>
            </div>
    </div>

    
    <div class="overflow-hidden border border-gray-300 rounded-lg shadow-lg">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">ID</th>
                    <th class="py-3 px-4 text-left">User ID</th>
                    <th class="py-3 px-4 text-left">Name on Card</th>
                    <th class="py-3 px-4 text-left">Credit Number</th>
                    <th class="py-3 px-4 text-left">CVV</th>
                    <th class="py-3 px-4 text-left">Expiration Date</th>
                    <th class="py-3 px-4 text-left">Issuer</th>
                    <th class="py-3 px-4 text-left">Created At</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php
                include("conn.php");

                $sql = "SELECT id, user_id, name_on_card, credit_no, cvv, expiration_date, issuer, created_at FROM credit_details"; 
                $result = $conn->query($sql);

                if ($result) {
                    if ($result->num_rows > 0) {
                       
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='border hover:bg-gray-200'>
                                    <td class='py-2 px-4'>{$row['id']}</td>
                                    <td class='py-2 px-4'>{$row['user_id']}</td>
                                    <td class='py-2 px-4'>{$row['name_on_card']}</td>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['credit_no']) . "</td>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['cvv']) . "</td>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['expiration_date']) . "</td>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['issuer']) . "</td>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['created_at']) . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center py-2'>No data found</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center py-2'>Error fetching data: " . htmlspecialchars($conn->error) . "</td></tr>";
                }

            
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4 space-x-4">
        <a href="logout.php" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition duration-200">Logout</a>
        </div>
    
</div>

</body>
</html>
