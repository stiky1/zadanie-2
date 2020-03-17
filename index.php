<!doctype html>
<html lang="sk">
    <head>
        <title>Zadanie-2</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
        <script src="script.js"></script>
    </head>
    <body>
    <script src="script.js"></script>
    <div class="topnav">
        <a class="active" href="index.php">ULOHA 3</a>
        <a href="index2.php">ULOHA 4</a>
    </div>


    <form method="post">
        <input type="radio" name="oblast" value="1">DOPRAVA |
        <input type="radio" name="oblast" value="2"> FINANCIE |
        <input type="radio" name="oblast" value="3"> ŠKOLSTVO |
        <input type="radio" name="oblast" value="4"> ZDRAVOTNÍCTVO |
        <input type="radio" name="oblast" value="0"> VŠETKY OBLASTI |
        <input type="radio" name="oblast" value="5"> INÉ
        <input type="submit" value="POTVRDIŤ VÝBER">
    </form>

    <?php
        echo "<table id='table' class='table'>";
        echo "<tr><th id='oblast' >OBLAST</th><th id='ministerstvo'>MINISTERSTVO</th><th id='minister'>MINISTER</th><th id='datumOD'>DATUM OD</th><th id='datumDO'>DATUM DO</th><th id='dlzka'>DLZKA</th></tr>";
        include "config.php";
        
        $select = $_POST["oblast"];

        if(isset($select)) {
            switch ($select) {
                case '1':
                    $obl = 1;
                    break;
                case '2':
                    $obl = 2;
                    break;
                case '3':
                    $obl = 3;
                    break;
                case '4':
                    $obl = 4;
                    break;
                case '5':
                    $obl = 5;
                    break;
                case '0':
                    $obl = 0;
                    break;
            }
        }
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->exec("set names utf8");
            if($obl != 0) {
                $data = "
                SELECT oblasti.oblast, utvary.nazov, osoby.meno, osoby.datumOD, osoby.datumDO, DATEDIFF(osoby.datumDO, osoby.datumOD) as days
                FROM osoby, utvary, oblasti
                WHERE utvary.id = osoby.id_utvary && utvary.oblast_id = oblasti.id && oblasti.id = $obl";
            } else {
                $data = "
                SELECT oblasti.oblast, utvary.nazov, osoby.meno, osoby.datumOD, osoby.datumDO, DATEDIFF(osoby.datumDO, osoby.datumOD) as days
                FROM osoby, utvary, oblasti
                WHERE utvary.id = osoby.id_utvary && utvary.oblast_id = oblasti.id";
            }
            $stmt = $conn->query($data);

            // set the resulting array to associative
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($result as $row) {
                printf("<tr><td>".$row["oblast"]."</td><td>".$row["nazov"]."</td><td>".$row["meno"]."</td><td>".$row["datumOD"]."</td><td>".$row["datumDO"]."</td><td>".$row["days"]."</td></tr>");
            }
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        echo "</table>";
        ?>
    </body>
</html>