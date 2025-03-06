<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Första php sida</title>
</head>
<body>
    <h1>PUCKO</h1>

    <table>
            <?php
            $pdo = new PDO('mysql:dbname=Violeta;host=localhost', "a22viojaDezSprid", 'hemligt');
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            if (isset($_GET["titelIn"])) {
                $titelIn = $_GET["titelIn"];

                // Call the stored PROCEDURE
                $stmt = $pdo->prepare("CALL getIncNamnByTitel(:titelIn)");
                $stmt->bindParam(':titelIn', $titelIn, PDO::PARAM_STR);
                $stmt->execute();

                // Display the results
                echo "<h5>Incidentens Namn efter Titel: $titelIn</h5>";

                while ($row = $stmt->fetch()) {
                    echo "<tr>";
                    echo "<td>".$row['namnIncident']."</td>";
                    echo "</tr>";
                } 
            }
            ?>  
        </table>

</body>

</html>