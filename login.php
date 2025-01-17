<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .active-label {
      font-weight: bold;
      color: #f6f6f6; 
    }

    .inactive-label {
      color: #6b7280; 
    }

    .switch-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #d6d8db; 
      padding: 5px;
      border-radius: 50px;
      width: 200px;
      margin: 0 auto; 
    }

    .switch-button {
      background-color: white;
      border-radius: 50px;
      width: 50%;
      padding: 10px;
      text-align: center;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .admin-active {
      background-color: #195ab6; 
      color: white;
    }

    .user-active {
      background-color: #5f3d99; 
      color: white;
    }

    .login-box {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); 
      border: 2px solid rgba(75, 85, 99, 0.1); 
      background-color: white;
    }

    .user-button {
      background-color: #5f3d99; 
    }

    .user-button:hover {
      background-color: #482a75; 
    }

    .admin-button {
      background-color: #195ab6;
    }

    .admin-button:hover {
      background-color: #143e88; 
    }
  </style>

  <script>
    function toggleLoginMode(mode) {
      const loginTitle = document.getElementById('login-title');
      const loginBtn = document.getElementById('login-btn');

      const userLabel = document.getElementById('user-label');
      const adminLabel = document.getElementById('admin-label');

      const userButton = document.getElementById('user-btn');
      const adminButton = document.getElementById('admin-btn');

      if (mode === 'admin') {
        loginTitle.textContent = 'Admin Login to Credit Details';
        loginBtn.textContent = 'Login as Admin';
        userLabel.classList.replace('active-label', 'inactive-label');
        adminLabel.classList.replace('inactive-label', 'active-label');
        adminButton.classList.add('admin-active');
        userButton.classList.remove('user-active');
        loginBtn.classList.remove('user-button');
        loginBtn.classList.add('admin-button');
      } else {
        loginTitle.textContent = 'User Login to Credit Details';
        loginBtn.textContent = 'Login as User';
        userLabel.classList.replace('inactive-label', 'active-label');
        adminLabel.classList.replace('active-label', 'inactive-label');
        userButton.classList.add('user-active');
        adminButton.classList.remove('admin-active');
        loginBtn.classList.remove('admin-button');
        loginBtn.classList.add('user-button');
      }
    }

    function showAlert(message) {
      alert(message);
    }
  </script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">
  <section class="login-box p-8 rounded-xl shadow-lg w-full max-w-md mx-4 sm:mx-8 md:mx-0">
    <h1 id="login-title" class="text-3xl font-bold text-indigo-600 mb-4 text-center">User Login to Credit Details</h1>

    <div class="switch-container mb-4">
      <div id="user-btn" class="switch-button user-active" onclick="toggleLoginMode('user')">
        <span id="user-label" class="active-label">User</span>
      </div>
      <div id="admin-btn" class="switch-button" onclick="toggleLoginMode('admin')">
        <span id="admin-label" class="inactive-label">Admin</span>
      </div>
    </div>

    <?php
    session_start();
    include("conn.php");

    $error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT user_id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $name, $hashed_password, $role);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role'] = $role;
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;

                if ($role == 'admin') {
                    header("Location: admin_data.php");
                } else {
                    header("Location: user_data.php");
                }
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No user found with this email.";
        }
        $stmt->close();
    }
    $conn->close();

    
    ?>

    

    <form class="space-y-6" action="" method="POST">
      <div class="space-y-1">
        <label for="email" class="text-gray-700">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent" required>
      </div>

      <div class="space-y-1">
        <label for="password" class="text-gray-700">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent" required>
      </div>

      <div class="text-right">
        <a href="#" class="text-sm text-indigo-600 hover:underline">Forgot password?</a>
      </div>

      <button id="login-btn" type="submit" class="w-full user-button text-white px-6 py-3 rounded-lg font-bold transition-colors">
        Login as User 
      </button>
    </form>

    <p class="text-center text-gray-600 mt-4">Don't have an account? <a href="signup.php" class="text-indigo-600 hover:underline">Sign up here</a></p>
  
<!-- Error message display -->
<?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>
</section>
</body>
</html>


