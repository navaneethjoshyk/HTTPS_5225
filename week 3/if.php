<?php
// magic.php
// Function that applies the FizzBuzz rules
function magicNumber($n) {
    if ($n % 15 === 0) return "FizzBuzz";
    if ($n % 3 === 0) return "Fizz";
    if ($n % 5 === 0) return "Buzz";
    return $n; // return the number itself
}

// Check for submitted input
$input = null;
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['random'])) {
        $input = rand(1, 1000);
        $result = magicNumber($input);
    } elseif (!empty($_POST['number'])) {
        $input = (int)$_POST['number'];
        $result = magicNumber($input);
    }
}
?>
<!doctype html>
<html>
<head><title>Magic Number Game</title></head>
<body>
    <h1>Magic Number Game</h1>
    <form method="post">
        <input type="number" name="number" placeholder="Enter a number">
        <button type="submit">Check</button>
        <button type="submit" name="random">Random</button>
    </form>

    <?php if ($result !== null): ?>
        <p>Input: <?php echo $input; ?></p>
        <p>Result: <?php echo $result; ?></p>
    <?php endif; ?>
</body>
</html>
