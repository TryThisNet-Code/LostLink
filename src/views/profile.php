<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <h2>Welcome <?= htmlspecialchars($_SESSION['user'])?>!</h2>
    <p>You are logged in</p>
    
    <a href="logout.php">Logout</a>
</body>
</html>