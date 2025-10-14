<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All schools</title>
</head>
<body>
    <h1>schools</h1>
    <div>
        <?php include('nav.php'); ?>
    </div>

    <hr>
    <div>
       <?php
        include('connect.php');
        $query = 'SELECT * FROM schools';
        $schools = mysqli_query($connect, $query);
        echo '<pre>' .print_r($schools) . '</pre>';

        foreach ($schools as $school) {
             echo $school['School Name'] . 
            '<form action="editschool.php" method="GET">
                <input type="hidden" name="id" value="' . $school['id'] . '">
                <input type="submit" value="EDIT">
            </form><br>';
            }   
        ?>

        <style>
            .Box{

            }
        </style>

    </div>
</body>
</html>