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
            <a href="index.php">ULOHA 3</a>
            <a class="active" href="index2.php">ULOHA 4</a>
        </div>

        <form method="post">
            <div class="form-group">
                <label for="obdobia">Vyber obdobie:</label>
                <select name="obdobie" id="obdobia">
                    <option value=""></option>
                    <option value="1">1990 - 1992</option>
                    <option value="2">1992 - 1994</option>
                    <option value="3">1994 - 1998</option>
                    <option value="4">1998 - 2002</option>
                    <option value="5">2002 - 2006</option>
                    <option value="6">2006 - 2010</option>
                    <option value="7">2010 - 2012</option>
                    <option value="8">2012 - 2016</option>
                    <option value="9">2016 - 2020</option>
                    <option value="10">2020 - ...</option>
                </select>
                <input type="submit" name="submit">
            </div>
        </form>

        <?php
        include "config.php";
        echo "<table id='table' class='table'>";
        echo "<tr><th id='nazov'>NÁZOV STRANY</th><th id='skratka'>SKRATKA STRANY</th><th id='pocet'>POČET ZÍSKANÝCH KRESIEL</th></tr>";

        $select = $_POST["obdobie"];

        if(isset($select)){
            switch ($select) {
                case '1':
                    $obdobie = 1;
                    break;
                case '2':
                    $obdobie = 2;
                    break;
                case '3':
                    $obdobie = 3;
                    break;
                case '4':
                    $obdobie = 4;
                    break;
                case '5':
                    $obdobie = 5;
                    break;
                case '6':
                    $obdobie = 6;
                    break;
                case '7':
                    $obdobie = 7;
                    break;
                case '8':
                    $obdobie = 8;
                    break;
                case '9':
                    $obdobie = 9;
                    break;
                case '10':
                    $obdobie = 10;
                    break;
                default:
                    break;
            }
        }
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->exec("set names utf8");
            if (!$obdobie) {
                $obdobie = 1;
            }
            $data = "
                        SELECT strany.nazov, strany.skratka, vysledky.kresla, vysledky.koalicia
                        FROM strany, vysledky
                        WHERE vysledky.id_volby = $obdobie && vysledky.id_strany = strany.id";
            $stmt = $conn->query($data);

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $kreslaK = 0;
            $kreslaO = 0;
            foreach($result as $row) {
                if($row["koalicia"] == 1) {
                    printf("<tr style='background-color: #b4bcbd'><td>" . $row["nazov"] . "</td><td>" . $row["skratka"] . "</td><td>" . $row["kresla"] . "</td></tr>");
                    $kreslaK = $kreslaK + $row["kresla"];
                } else {
                    printf("<tr><td>" . $row["nazov"] . "</td><td>" . $row["skratka"] . "</td><td>" . $row["kresla"] . "</td></tr>");
                    $kreslaO = $kreslaO + $row["kresla"];
                }
            }
            echo "</table>";
            echo "<br>";
            echo "<h2>POCET KRESIEL KOALÍCIE: $kreslaK    |    POCET KRESIEL OPOZÍCIE: $kreslaO</h2>";
            echo "<br>";

            echo "<table id='table2' class='table'>";
            echo "<tr><th>VLÁDA</th><th>DÁTUM VZNIKU</th><th>DÁTUM ZÁNIKU</th></tr>";
            $data2 = "
                        SELECT  vlady.datumOD, vlady.datumDO
                        FROM vlady
                        WHERE vlady.id_volby = $obdobie";
            $stmt2 = $conn->query($data2);
            $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            $counter = 1;
            foreach($result2 as $row) {
                printf("<tr><td>" . $counter. "</td><td>" . $row["datumOD"] . "</td><td>" . $row["datumDO"] . "</td></tr>");
                $counter ++;
            }
            echo "</table>";
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        ?>
    </body>
</html>