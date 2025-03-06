<?php
session_start();

$expectedUsername = 'a22viojaDezSprid';
$expectedPassword = 'hemligt';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $submittedUsername = $_POST['username'];
    $submittedPassword = $_POST['password'];

    // Check if the submitted values match the expected values
    if ($submittedUsername === $expectedUsername && $submittedPassword === $expectedPassword) {
        $_SESSION['logged_in'] = true;
        $_SESSION['hemligt'] = $submittedUsername;

        // if succesfull login
        header('Location: firstProject.php');
        exit;
    } else {
        // Invalid login 
        echo "Invalid username or password. Please try again.";
        exit;
    }
}

// Check if the user is already logged in
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

if (isset($_POST['logout'])) {
    // Destroy the session
    session_destroy();
    // Redirect to inloggning.php after logout
    header('Location: inloggning.php');
    exit;
}
?>

<!-- Add a logout button if the user is logged in -->
<?php if ($loggedIn): ?>
    <form action="firstProject.php" method="POST">
        <input type="submit" name="logout" value="Logout">
    </form>
<?php endif; ?>



<?php
//DELETE FROM DESINFOSPRIDARE part is over becouse of delete action will be processed first,
//and then the updated table will be displayed. 
//it will work immediately without the need to refresh the page
$pdo = new PDO('mysql:dbname=Violeta;host=localhost', "a22viojaDezSprid", 'hemligt');
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    if(isset($_POST["delete_namn"]) && isset($_POST["delete_nr"])){

        $delete_namn = $_POST ["delete_namn"];
        $delete_nr = $_POST ["delete_nr"];

        $querystring = 'DELETE from DesinfoSpridare WHERE namn = :delete_namn AND nr = :delete_nr' ;

        $stmt =  $pdo->prepare( $querystring);
        $stmt->bindParam(':delete_namn', $delete_namn);
        $stmt->bindParam(':delete_nr',  $delete_nr);
        $stmt->execute();
    }
  

?>


<?php
//DELETE FROM RAPPORT MED LÄNK
if(isset($_GET["delete_datum"]) && isset($_GET["delete_titel"])){
    $deleteDatum = $_GET["delete_datum"];
    $deleteTitel = $_GET["delete_titel"];

    $querystring = 'DELETE FROM Rapport WHERE datum = :deleteDatum AND titel = :deleteTitel'; 
    $stmt = $pdo->prepare($querystring);
    $stmt->bindParam(':deleteDatum', $deleteDatum);
    $stmt->bindParam(':deleteTitel', $deleteTitel);
    
    if ($stmt->execute()) {
        header('Location: firstProject.php');
        exit;
    } else {
        echo "Error deleting Rapport.";
    }
}
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
    <h2>Ange nytt Agent</h2>
   
    <form action = "firstProject.php" method="POST">
        <div>
            <label> 
                Dezinformationsspridare Namn: <input type="text" name="namn">
            </label><br/>
        </div>
        <div>
            <label> 
                Nummer: <input type="text" name="nr">
            </label><br/>
        </div>
        <div>
            <label> 
            Specialite: <input type="text" name="specialite">
            </label><br/>
        </div>
        <div>
            <label> 
                Incident Namn: <input type="text" name="namnInc">
            </label><br/>
        </div>
        <div>
            <label> 
                Incident Nr: <input type="text" name="nrInc">
            </label><br/><br/> 
        </div>
        <input type="submit" value="skicka">
        <br/>
    </form>

    <div>
            <h2>Desinformations Agenter</h2>
    </div>

    <table border=1>
        <tr>
            <th>Name</th>
            <th>Nr</th>
        </tr>
            <?php
                /*This is for the table with data from database*/ 
                if(isset($_POST["namn"])){

                    $querystring='INSERT INTO DesinfoSpridare(namn,nr,specialite,namnInc,nrInc) VALUES (:namn,:nr,:specialite,:namnInc,:nrInc)';
                    $stmt = $pdo->prepare($querystring);
                    $stmt->bindParam(':namn', $_POST['namn']);
                    $stmt->bindParam(':nr', $_POST['nr']);
                    $stmt->bindParam(':specialite', $_POST['specialite']);
                    $stmt->bindParam(':namnInc', $_POST['namnInc']);
                    $stmt->bindParam(':nrInc', $_POST['nrInc']);
                    $stmt->execute();
                }
                
                foreach($pdo->query( 'SELECT * FROM DesinfoSpridare;' ) AS $row){

                    echo "<tr>";
                    echo "<td>".$row['namn']."</td>";
                    echo "<td>".$row['nr']."</td>";
                    echo "</tr>";  
                }
                
            ?>        
    </table>
 
    <?php
        foreach ($pdo->query('SELECT * FROM DesinfoSpridare;') as $row) {
            echo "<a href='link.php?namn=" . $row["namn"] . "&nr=" . $row["nr"] . "&specialite=" . $row["specialite"] . "&namnInc=" . $row["namnInc"] . "&nrInc=" . $row["nrInc"] . "'> Full INFO om  " . $row["namn"] . " </a><br>";
        }
    ?>

    <div>
        <br><br>
        <form action="firstProject.php" method="POST">
            <label>
                Ta bort agenten:
                <input type="text" name="delete_namn" placeholder="Namn">
                <input type="text" name="delete_nr" placeholder="Nr">
            </label>
            <input type="submit" value="DELETE">
        </form>
    </div>
  <table>
    <div>
        <h2>Sök på namn</h2>
        <form action="firstProject.php" method="POST">
            <label for="Namn">Agentens Namn:</label>
            <input type="text" name="Namn">
            <input type="submit" value="Sök">
           
        </form>
    </div>

        <?php

            /*This is for fritext search "4.sökning i databasen med antingen fritextsökning"
            I filtrate by the name of the Agent and retrieve the */
           
            if(isset($_POST["Namn"])){
                
                $querystring = 'SELECT * FROM DesinfoSpridare WHERE namn=:namn;';
                $stmt = $pdo->prepare($querystring);
                $stmt->bindParam(':namn', $_POST['Namn']); 
                $stmt->execute();
            
                foreach($stmt as $key => $row){
                    echo "<div>";
                    echo $row['namn']." ".$row['nr']." ".$row['specialite']." ".$row['namnInc']." ".$row['nrInc'];
                    echo "</div>";
                }
            }
        ?>
    </table>

     <br><br>   
     <h2>Rapport Tabellen</h2>

        <table border=1>
            <?php
            /*This is for the table with data from database*/ 
            if(isset($_POST["datum"])){

                $querystring='INSERT INTO Rapport(datum,titel,namnIncident,nrIncident) VALUES (:datum,:titel,:namnIncident,:nrIncident)';
                $stmt = $pdo->prepare($querystring);
                $stmt->bindParam(':datum', $_POST['datum']);
                $stmt->bindParam(':titel', $_POST['titel']);
                $stmt->bindParam(':namnIncident', $_POST['namnIncident']);
                $stmt->bindParam(':nrIncident', $_POST['nrIncident']);
                $stmt->execute();
            }
                /*These are the names for columns */
                echo "<th>"."Datum"."</th>";
                echo "<th>"."Titel"."</th>";
                echo "<th>"."Incident Namn"."</th>";
                echo "<th>"."Incident Nr"."</th>";
                echo "<th>"."Action"."</th>";
            
            foreach($pdo->query( 'SELECT * FROM Rapport;' ) AS $row){

                echo "<tr>";
                echo "<td>".$row['datum']."</td>";
                echo "<td>".$row['titel']."</td>";
                echo "<td>".$row['namnIncident']."</td>";
                echo "<td>".$row['nrIncident']."</td>";
                echo "<td><a href='firstProject.php?delete_id=".$row['datum']."&delete_titel=".$row['titel']."'>Ta bort</a></td>"; 
                echo "</tr>";
            } 
            ?>
        </table>

        <div>
                <br><br>
                <form action="search.php" method="GET">
                    <label for="titelIn">Sök efter Titel:</label>
                    <input type="varchar" name="titelIn">
                    <input type="submit" value="Sök">
                </form>
        </div>
    
        <br><br>

    <div>
        <form action="firstProject.php" method="POST">
            <label for="datum">Välj datum:</label>
            <select name="datum">
            <?php
                $datesQuery = $pdo->query('SELECT datum FROM Rapport;');
                while ($dateRow = $datesQuery->fetch()) {
                    echo "<option value='" . $dateRow['datum'] . "'>" . $dateRow['datum'] . "</option>";
                }
                ?>
            </select>
            <input type="submit" value="Få titeln">
        </form>
    </div>

    <table border="1">
        <?php
        if (isset($_POST["datum"])) {
            $selectedDatum = $_POST["datum"];

            // Retrieve data for the selected date
            $querystring = 'SELECT * FROM Rapport WHERE datum = :selectedDatum';
            $stmt = $pdo->prepare($querystring);
            $stmt->bindParam(':selectedDatum', $selectedDatum);
            $stmt->execute();

            // Display the table header
            echo "<th>" . "Datum" . "</th>";
            echo "<th>" . "Titel" . "</th>";

            // Display data for the selected date
            while ($row = $stmt->fetch()) {
                echo "<tr>";
                echo "<td>" . $row['datum'] . "</td>";
                echo "<td>" . $row['titel'] . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
    <br><br>
</body>

</html>