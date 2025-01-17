<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
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
</head>
<body class="bg-gray-100">

  <section class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
      <h1 class="text-3xl font-bold text-center text-indigo-600 mb-6">Sign Up for Data</h1>

      <?php
      session_start();
      include("conn.php");

     
      $error = '';
      $success = '';

    
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $name = $_POST['name'];
          $email = $_POST['email'];
          $password = $_POST['password'];
          $confirm_password = $_POST['confirm-password'];
          $role = 'user'; 


          if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
              $error = "All fields are required.";
          } elseif ($password !== $confirm_password) {
              $error = "Passwords do not match.";
          } else {
         
              $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
              $stmt->bind_param("s", $email);
              $stmt->execute();
              $stmt->store_result();

              if ($stmt->num_rows > 0) {
                  $error = "Email already exists.";
              } else {
                  
                  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                  $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                  $insert_stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

                  if ($insert_stmt->execute()) {
                      $success = "Registration successful! You can now log in.";

                  } else {
                      $error = "Error occurred while registering.";
                  }

                  $insert_stmt->close();
              }
              $stmt->close();
          }
      }

      $conn->close();
      ?>

   
      

      <form action="" method="POST" class="space-y-6">
        <div class="space-y-1">
          <label for="name" class="text-gray-700">Full Name</label>
          <input type="text" name="name" id="name" placeholder="Enter your full name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
        </div>

        <div class="space-y-1">
          <label for="email" class="text-gray-700">Email</label>
          <input type="email" name="email" id="email" placeholder="Enter your email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
        </div>

        <div class="space-y-1">
          <label for="password" class="text-gray-700">Password</label>
          <input type="password" name="password" id="password" placeholder="Create a password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
        </div>

        <div class="space-y-1">
          <label for="confirm-password" class="text-gray-700">Confirm Password</label>
          <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm your password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-indigo-700 transition-colors animated-button">
          Sign Up
        </button>
      </form>

      <p class="text-center text-gray-600 mt-6">Already have an account? <a href="login.php" class="text-indigo-600 hover:underline">Login here</a></p>
      <?php if ($error): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-0" role="alert">
              <strong class="font-bold">Error!</strong>
              <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
          </div>
      <?php elseif ($success): ?>
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-0" role="alert">
              <strong class="font-bold">Success!</strong>
              <span class="block sm:inline"><?php echo htmlspecialchars($success); ?></span>
          </div>
      <?php endif; ?>
    </div>
   
  </section>

</body>
</html>
