<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

include("conn.php");

$error = '';
$success = '';

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name_on_card = $_POST['name_on_card'];
    $credit_no =  $_POST['credit_no']; 
    $cvv = $_POST['cvv'];
    $expiration_date = $_POST['expiration_date'];
    $issuer = $_POST['issuer'];

    // Basic validation
    if (empty($name_on_card) || empty($credit_no) || empty($cvv) || empty($expiration_date) || empty($issuer)) {
        $error = "All fields are required.";
    } elseif (!preg_match("/^\d{16}$/", str_replace('-', '',$credit_no))) {
        $error = "Credit number must be 16 digits.";
    } elseif (!preg_match("/^\d{3,4}$/", $cvv)) {
        $error = "CVV must be 3 or 4 digits.";
    } else {
        $stmt = $conn->prepare("INSERT INTO credit_details (user_id, name_on_card, credit_no, cvv, expiration_date, issuer) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ississ", $user_id, $name_on_card, $credit_no, $cvv, $expiration_date, $issuer);

        if ($stmt->execute()) {
            $success = "Credit details added successfully.";
        } else {
            $error = "Error occurred while adding credit details.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Credit Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes buttonGlow {
            0% {
                box-shadow: 0 0 5px rgba(99, 102, 241, 0.6);
            }
            50% {
                box-shadow: 0 0 20px rgba(99, 102, 241, 1);
            }
            100% {
                box-shadow: 0 0 5px rgba(99, 102, 241, 0.6);
            }
        }

        .animated-button {
            animation: buttonGlow 2s ease-in-out infinite;
        }
    </style>
    <script>
        
        function formatCreditCardNumber(input) {
            const value = input.value.replace(/\D/g, ''); 
            const formattedValue = value.replace(/(.{4})/g, '$1-').slice(0, 19); 
            input.value = formattedValue.trim(); 
        }
    </script>
</head>
<body class="bg-gray-100">
    
    <section class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-3xl font-bold text-center text-indigo-600 mb-6">Add Credit Details</h2>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php elseif ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline"><?php echo htmlspecialchars($success); ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                <div class="space-y-1">
                    <label for="name_on_card" class="text-gray-700">Name on Card</label>
                    <input type="text" name="name_on_card" id="name_on_card" placeholder="Enter name on card" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                </div>

                <div class="space-y-1">
                    <label for="credit_no" class="text-gray-700">Credit Number</label>
                    <input type="text" name="credit_no" id="credit_no" placeholder="Enter credit number" required maxlength="19" oninput="formatCreditCardNumber(this)" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                </div>

                <div class="space-y-1">
                    <label for="cvv" class="text-gray-700">CVV</label>
                    <input type="text" name="cvv" id="cvv" placeholder="Enter CVV" required maxlength="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                </div>

                <div class="space-y-1">
                    <label for="expiration_date" class="text-gray-700">Expiration Date (YYYY-MM-DD)</label>
                    <input type="date" name="expiration_date" id="expiration_date" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                </div>

                <div class="space-y-1">
                    <label for="issuer" class="text-gray-700">Issuer</label>
                    <input type="text" name="issuer" id="issuer" placeholder="Enter issuer name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-indigo-700 transition-colors animated-button">
                    Add Credit Details
                </button>
            </form>
            <div class="flex justify-center space-x-4 mr-6 mt-7">
                <a href="user_data.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-bold hover:bg-gray-400 transition-colors">
                    Back to User Admin</a>
                <a href="logout.php" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition duration-200">Logout</a>
   
            </div>
        </div>
    </section>
</body>
</html>
