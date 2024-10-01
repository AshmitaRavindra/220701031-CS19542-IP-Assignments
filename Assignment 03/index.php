<?php
// Database configuration
$host = 'localhost';
$dbname = 'event_management';
$user = 'root';
$pass = 'Sanukavu@1424';

// Connect to the database
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Session management
session_start();

// User registration
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $role = $_POST['role']; // Role from the form ('user' or 'admin')

    // Check if the username already exists
    $checkUserSql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $checkUserSql);

    if (mysqli_num_rows($result) > 0) {
        echo "Username already exists. Please choose a different username.";
    } else {
        // Insert user into the database
        $sql = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')";
        if (mysqli_query($conn, $sql)) {
            echo "Registration successful.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// User login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user from the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Store user role in session
        echo "Login successful. Welcome, " . $_SESSION['username'];
    } else {
        echo "Invalid username or password.";
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Event management for admin
if (isset($_POST['create_event']) && $_SESSION['role'] === 'admin') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $price = $_POST['price'];

    // Insert event into the database
    $sql = "INSERT INTO events (title, description, date, time, location, price) 
            VALUES ('$title', '$description', '$date', '$time', '$location', '$price')";
    if (mysqli_query($conn, $sql)) {
        echo "Event created successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Ticket booking for users
if (isset($_POST['book_ticket']) && $_SESSION['role'] === 'user') {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'];

    // Insert booking into the database
    $sql = "INSERT INTO bookings (user_id, event_id) VALUES ('$user_id', '$event_id')";
    if (mysqli_query($conn, $sql)) {
        echo "Ticket booked successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch all events for listing
$sql = "SELECT * FROM events";
$events = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Management System</title>
</head>
<body>
    <h1>Event Management System</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="events.php">Events</a>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="index.php?logout=true">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
        <?php else: ?>
            <a href="index.php#login">Login/Register</a>
        <?php endif; ?>
    </nav>

    <?php if (!isset($_SESSION['username'])): ?>
        <!-- User Registration -->
        <h3>User Registration</h3>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="email" name="email" placeholder="Email" required>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="register">Register</button>
        </form>

        <!-- User Login -->
        <h3>User Login</h3>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
    <?php endif; ?>

    <!-- Event Listing for All Users -->
    <div id="events">
        <h2>Upcoming Events</h2>
        <?php while ($event = mysqli_fetch_assoc($events)): ?>
            <div>
                <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                <p><?php echo htmlspecialchars($event['description']); ?></p>
                <p>Date: <?php echo htmlspecialchars($event['date']); ?></p>
                <p>Time: <?php echo htmlspecialchars($event['time']); ?></p>
                <p>Location: <?php echo htmlspecialchars($event['location']); ?></p>
                <p>Price: $<?php echo htmlspecialchars($event['price']); ?></p>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                    <form method="POST" action="">
                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                        <button type="submit" name="book_ticket">Book Ticket</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <!-- Admin Dashboard -->
        <div id="admin_dashboard">
            <h2>Admin Dashboard</h2>
            <h3>Create Event</h3>
            <form method="POST" action="">
                <input type="text" name="title" placeholder="Event Title" required>
                <textarea name="description" placeholder="Event Description" required></textarea>
                <input type="date" name="date" required>
                <input type="time" name="time" required>
                <input type="text" name="location" placeholder="Event Location" required>
                <input type="number" name="price" placeholder="Ticket Price" required>
                <button type="submit" name="create_event">Create Event</button>
            </form>
        </div>
    <?php endif; ?>

    <?php
    // Close the database connection
    mysqli_close($conn);
    ?>
</body>
</html>
