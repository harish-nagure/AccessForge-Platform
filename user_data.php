<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

include("conn.php");

$user_name = isset($_SESSION['name']) ? $_SESSION['name'] : "No name found";
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : "No email found"; 
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
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Welcome, <span class="text-indigo-600"><?php echo htmlspecialchars($user_name); ?></span>!</h2>

        <div class="text-center mb-6">
            <p class="text-2xl font-bold text-gray-500 mb-2">Data Table</p>
            <p class="text-xl text-gray-500">User ID: <span class="font-semibold"><?php echo htmlspecialchars($user_id); ?></span></p>

        </div>
    </div>

    <div class="overflow-hidden border border-gray-300 rounded-lg shadow-lg">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">ID</th>
                    <th class="py-3 px-4 text-left">Name on Card</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Credit Number</th>
                    <th class="py-3 px-4 text-left">CVV</th>
                    <th class="py-3 px-4 text-left">Expiration Date</th>
                    <th class="py-3 px-4 text-left">Issuer</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php
                $user_id = $_SESSION['user_id'];
                $sql = "SELECT id, name_on_card, credit_no, cvv, expiration_date, issuer FROM credit_details WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id); 
                $stmt->execute();
                $result = $stmt->get_result();

                
                if ($result) {
                    if ($result->num_rows > 0) {
                        // Fetch data
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='border hover:bg-gray-200'>
                                    <td class='py-2 px-4'>{$row['id']}</td>
                                    <td class='py-2 px-4'>{$row['name_on_card']}</td>
                                    <td class='py-2 px-4'>{$user_email}</td> 
                                    <td class='py-2 px-4'>{$row['credit_no']}</td>
                                    <td class='py-2 px-4'>{$row['cvv']}</td>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['expiration_date']) . "</td>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['issuer']) . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center py-2'>No data found</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center py-2'>Error fetching data: " . $conn->error . "</td></tr>";
                }

               
                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <div class="text-center mt-4 space-x-4">
        <a href="logout.php" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition duration-200">Logout</a>
        <a href="credit_form.php" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-200">Add new Credit Data</a>
    </div>


</div>

</body>
</html>
