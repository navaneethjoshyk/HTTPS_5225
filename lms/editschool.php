<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit school</title>
</head>
<body>
    <h1>Edit school</h1>
    <hr>
    <div>
        <?php
         include('nav.php'); ?>
    </div>

    <hr>
    <div>

        <?php 
            require('connect.php');

            if($_SERVER['REQUEST_METHOD'] == 'GET' ){
                $id = $_GET['id'];
                $query = "SELECT * FROM schools WHERE id = $id";
                $result = mysqli_query($connect, $query);
                $school = $result -> fetch_assoc(); ;
            }

            if(isset($_POST['addSchool'])){
                $boardName = $_POST['boardName'];
                $schoolName = $_POST['schoolName'];     
                $schoolNumber = $_POST['schoolNumber'];
                $schoolLevel = $_POST['schoolLevel'];

                $query = "UPDATE 
                            schools SET
                             (  `Board Name`,
                                `School Name`, 
                                `School Number`,
                                `School Level`) VALUES 
                             (  '$boardName',
                                '$schoolName',
                                '$schoolNumber',
                                '$schoolLevel'
                                )";
                $school = mysqli_query($connect, $query);
                if($school){
                    echo "School added successfully";
                } else {
                    echo "Error adding school: " . mysqli_error($connect);
                }   
            }

        ?>

        <form action="addschool.php" method="POST">
            <label for="boardName">Board Name:</label>
            <input type="text" name="boardName" placeholder="Boardname" value="<?php echo $school['Board Name']; ?>" required>
            <br>
            <label for="schoolname">School Name:</label>
            <input type="text" name="schoolName" placeholder="schoolname" required>
            <br>
            <label for="SchoolNumber">SchoolNumber:</label>
            <input type="text" name="schoolNumber" id="schoolNumber" required>
            <br>
            <label for="SchoolLevel">SchoolLevel:</label>
            <input type="text" name="schoolLevel" id="schoolLevel" required>
            <br>
            <input type="submit" name= "EditSchool" value="Edit School">

        </form>
    </div>
</body>
</html>