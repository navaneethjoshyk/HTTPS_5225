<?php
// exercise.php
// Fetch user data
function getUsers() {
    $url = "https://jsonplaceholder.typicode.com/users";
    $data = file_get_contents($url); // simple HTTP GET
    return json_decode($data, true); // decode JSON into array
}

$users = getUsers();
?>
<!doctype html>
<html>
<head><title>Users List</title></head>
<body>
    <h1>Users Directory</h1>

    <?php
    if (empty($users)) {
        echo "<p>No users found.</p>";
    } else {
        // Loop through the array with a FOR loop
        for ($i = 0; $i < count($users); $i++) {
            $u = $users[$i];
            echo "<h2>" . htmlspecialchars($u['name']) . "</h2>";
            echo "<p>Email: " . htmlspecialchars($u['email']) . "</p>";
            
            $addr = $u['address'];
            echo "<p>Address: " 
                 . htmlspecialchars($addr['street']) . ", "
                 . htmlspecialchars($addr['suite']) . ", "
                 . htmlspecialchars($addr['city']) . " "
                 . htmlspecialchars($addr['zipcode'])
                 . "</p>";
            echo "<hr>";
        }
    }
    ?>
</body>
</html>
