<html>
<head>
<meta charset="utf-8" >
<title>Generator grafiku</title>
<style id="table_style">
    table, tr, th{
        border: 2px solid #ff6550;
        color: black;
    }

    #tabela tr:nth-child(4n + 1) td,
    #tabela tr:nth-child(4n + 2) td,
    #tabela tr:nth-child(4n + 3) td {
        background-color: #ffa;
    }

    #tabela tr:nth-child(4n + 4) td,
    #tabela tr:nth-child(4n + 5) td {
        background-color: #ffa;
        filter: brightness(60%);
    }

    #tabelaF tr:nth-child(4n + 1) td,
    #tabelaF tr:nth-child(4n + 2) td {
        background-color: #ffa;
    }

    #tabelaF tr:nth-child(4n + 3) td,
    #tabelaF tr:nth-child(4n + 4) td {
        background-color: #ffa;
        filter: brightness(60%);
    }

    tr:hover{
        filter: brightness(60%);
    }

    html, body {
        height: 100%;
    }

    html {
        display: table;
        margin: auto;
    }

    body {
        display: table-cell;
        vertical-align: middle;
        background-color: rgb(44, 44, 44);
        color: white;
    }

    button{
        width: 100%;
    }
</style>

<script>
    function PrintTable() {
        var printWindow = window.open('', '', 'height=900,width=1200');
        printWindow.document.write('<html><head><title>Generator grafików</title>');
        var table_style = document.getElementById("table_style").innerHTML;
        printWindow.document.write('<style type = "text/css">');
        printWindow.document.write(table_style);
        printWindow.document.write('</style>');
        printWindow.document.write('</head>');
        printWindow.document.write('<body>');
        var divContents = document.getElementById("tabelka").innerHTML;
        printWindow.document.write(divContents);
        printWindow.document.write('</body>');
 
        printWindow.document.write('</html>');
        printWindow.document.close();
        printWindow.print();
    }

    function change(a){
        var lH = document.getElementById("lacznieH").innerText.substr(document.getElementById("lacznieH").innerText.length - 3);
        var iloscDni = parseInt(lH) / 24;
        var wybranaOs = parseInt(((a) / iloscDni) / 2);
        
        var ilH = parseInt(document.getElementById("sumH" + wybranaOs).innerText.slice(0, -1));
        

        if(document.getElementById(a).innerText == ""){
            document.getElementById(a).innerText = "12p";
            document.getElementById("sumH" + wybranaOs).innerText = ilH + 12 + "h";
        }else{
            document.getElementById(a).innerText = "";
            document.getElementById("sumH" + wybranaOs).innerText = ilH - 12 + "h";
        }

        var temp = 0;
        var total = 0;
        while(true){
            if(document.getElementById("sumH" + temp) !== null){
                total += parseInt(document.getElementById("sumH" + temp).innerText.slice(0, -1));
                temp++;
            }else{
                break;
            }
        }
        if(total == lH){
            document.getElementById("lacznieH").style["color"] = "black";
        }else{
            document.getElementById("lacznieH").style["color"] = "red";
        }
        document.getElementById("lacznieH").innerText = total + "/" + lH;
    }
</script>
</head>
<body>
<?php
$miesiac = array( '', 'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień' );
if(!empty($_GET["ilOs"]) || !empty($_POST["0|i"])){
    if(empty($_POST)){
        $monthChoosen = substr($_GET["mies"], 5);
        $yearChoosen = substr($_GET["mies"], 0, 4);
        $choosenDate = mktime(0, 0, 0, (int)$monthChoosen, 1, (int)$yearChoosen);

        $days = date("t", strtotime($choosenDate));

        $cspan = $days+1;
        echo '<table border="1px" id="tabela"><tr>';
        echo '<td colspan="'.$cspan.'" style="text-align: center;">'. $miesiac[date("n", $choosenDate)] . '</td></tr><tr>';
                
        echo '<td>Kto</td>';
        for($x = 1; $x <= $days; $x++){
            $tempDate = mktime(0, 0, 0, (int)$monthChoosen, $x, (int)$yearChoosen);
            if(date("D", $tempDate) == 'Sat' || date("D", $tempDate) == 'Sun'){
                echo '<td style="filter: brightness(60%);">'. $x .'</td>';
            }else{
                echo '<td>'. $x .'</td>';
            }
        }
        echo '</tr>';
        echo '<form name="form1" id="form1" method="post" action="index.php?ilOs=0&mies='.$_GET["mies"].'">';

        $ilOs = $_GET["ilOs"] - 1;

        for($y = 0; $y <= $ilOs; $y++){
            echo '<tr><td rowspan="2"><input type="text" name="'.$y.'|i"></td>';
            for($x = 1; $x <= $days; $x++){
                $tempDate = mktime(0, 0, 0, (int)$monthChoosen, $x, (int)$yearChoosen);
                if(date("D", $tempDate) == 'Sat' || date("D", $tempDate) == 'Sun'){
                        echo '<td style="filter: brightness(60%);"><input type="checkbox" name="'.$y.'|'.$x.'|1"></td>';
                }else{
                    echo '<td><input type="checkbox" name="'.$y.'|'.$x.'|1"></td>';
                }
            }
            echo '</tr>';
            echo '<tr>';
            for($x = 1; $x <= $days; $x++){
                $tempDate = mktime(0, 0, 0, (int)$monthChoosen, $x, (int)$yearChoosen);
                if(date("D", $tempDate) == 'Sat' || date("D", $tempDate) == 'Sun'){
                        echo '<td style="filter: brightness(60%);"><input type="checkbox" name="'.$y.'|'.$x.'|2"></td>';
                }else{
                    echo '<td><input type="checkbox" name="'.$y.'|'.$x.'|2"></td>';
                }
            }
            echo '</tr>';
        }
        

        echo '</table><input name="ilo" type="text" value="'.$ilOs.'" style="display: none;">';
        echo '<input type="submit" name="submit" value="Stwórz grafik!" style="width: 100%;">';
        echo '</form><br><button onclick='. "'". 'window.open("index.php","_self")'. "'" .'>Od nowa!</button>';

    }else{
        $monthChoosen = substr($_GET["mies"], 5);
        $yearChoosen = substr($_GET["mies"], 0, 4);
        $choosenDate = mktime(0, 0, 0, (int)$monthChoosen, 1, (int)$yearChoosen);
        $days = date("t", $choosenDate);

        $cspan = $days+1;
        echo '<a style="color: gray;">*Możesz kliknąć w komórki by edytować przypisane zmiany</a>';
        echo '<div id="tabelka"><table border="1px" id="tabelaF"><tr>';
        echo '<td colspan="'.$cspan.'" style="text-align: center;">'. $miesiac[date("n", $choosenDate)] . '</td><td rowspan="2">H / miesiąc</td></tr><tr>';
        
        echo '<td>Kto</td>';
        for($x = 1; $x <= $days; $x++){
            $tempDate = mktime(0, 0, 0, (int)$monthChoosen, $x, (int)$yearChoosen);
            if(date("D", $tempDate) == 'Sat' || date("D", $tempDate) == 'Sun'){
                echo '<td style="filter: brightness(60%);">'. $x .'</td>';
            }else{
                echo '<td>'. $x .'</td>';
            }        
        }
        echo '</tr>';

        $iloscOsob = $_POST["ilo"];
        
        $zmiany = array(
            
        );

        for($y = 0; $y <= $iloscOsob; $y++){
            $tempName = $y.'|i';
            array_push($zmiany, array($_POST[$tempName]));

            for($x = 1; $x <= $days; $x++){
                array_push($zmiany[$y], "0");
            }
        }

        $security = 0;
        while(true){
            $security++;
            for($y = 0; $y <= $iloscOsob; $y++){
                for($x = 1; $x <= $days; $x++){
                    $zmiany[$y][$x] = "0";
                }
            }

            $przerabianyDzien = 1;
            $proby = 0;
            while($przerabianyDzien != $days + 1){
                $proby++;
                if($proby >= 900){
                    break;
                }
                $osoba = rand(0, $iloscOsob);
                $tempName = $osoba . "|". $przerabianyDzien."|2";
                if(isset($_POST[$tempName])){
                    continue;
                }
                if($zmiany[$osoba][$przerabianyDzien-1] == "2" && $zmiany[$osoba][$przerabianyDzien-2] == "2" && $zmiany[$osoba][$przerabianyDzien-3] == "2"){
                    continue;
                }
                if($zmiany[$osoba][$przerabianyDzien-1] == "1"){
                    continue;
                }
                if($zmiany[$osoba][$przerabianyDzien] == "1"){
                    continue;
                }
                $zmiany[$osoba][$przerabianyDzien] = "2";
                $przerabianyDzien++;
    
            }
            $przerabianyDzien = 1;
            $proby = 0;
            while($przerabianyDzien != $days + 1){
                $proby++;
                if($proby >= 900){
                    break;
                }

                $osoba = rand(0, $iloscOsob);
                $tempName = $osoba . "|". $przerabianyDzien."|1";
                if(isset($_POST[$tempName])){
                    continue;
                }
                if($zmiany[$osoba][$przerabianyDzien-1] == "1" && $zmiany[$osoba][$przerabianyDzien-2] == "1" && $zmiany[$osoba][$przerabianyDzien-3] == "1"){
                    continue;
                }
                if($zmiany[$osoba][$przerabianyDzien-1] == "2"){
                    continue;
                }
                if($zmiany[$osoba][$przerabianyDzien] == "2"){
                    continue;
                }
                $zmiany[$osoba][$przerabianyDzien] = "1";
                $przerabianyDzien++;
            }

            if($przerabianyDzien >= $days){
                break;
            }else if($security >= 50){
                echo '<script>alert("Nie udało się zrobić grafiku*");</script>';
                echo '<script>alert("Ta wiadomość została stworzona na wypadek gryby algorytm się zapętlił.");</script>';
                echo '<script>alert("W takim wypadku może brakować osób do pokrycia zmian.");</script>';
                break;
            }
        }

        $idGen = 0;
        for($y = 0; $y <= $iloscOsob; $y++){
            $iloscH = 0;
            echo '<tr><td rowspan="2">'. $zmiany[$y][0] .'</td>';
            for($x = 1; $x <= $days; $x++){
                if($zmiany[$y][$x] == "1"){
                    $tempDate = mktime(0, 0, 0, (int)$monthChoosen, $x, (int)$yearChoosen);
                    if(date("D", $tempDate) == 'Sat' || date("D", $tempDate) == 'Sun'){
                                echo '<td style="filter: brightness(60%);" id="'.$idGen.'" onclick="change('.$idGen.')">12p</td>';
                    }else{
                        echo '<td id="'.$idGen.'" onclick="change('.$idGen.')">12p</td>';
                    }
                    $iloscH += 12;
                }else if($zmiany[$y][$x] == "2"){
                    $tempDate = mktime(0, 0, 0, (int)$monthChoosen, $x, (int)$yearChoosen);
                    if(date("D", $tempDate) == 'Sat' || date("D", $tempDate) == 'Sun'){
                                echo '<td style="filter: brightness(60%);" id="'.$idGen.'" onclick="change('.$idGen.')"></td>';
                    }else{
                        echo '<td id="'.$idGen.'" onclick="change('.$idGen.')"></td>';
                    }
                }else{
                    $tempDate = mktime(0, 0, 0, (int)$monthChoosen, $x, (int)$yearChoosen);
                    if(date("D", $tempDate) == 'Sat' || date("D", $tempDate) == 'Sun'){
                                echo '<td style="filter: brightness(60%);" id="'.$idGen.'" onclick="change('.$idGen.')"></td>';
                    }else{
                        echo '<td id="'.$idGen.'" onclick="change('.$idGen.')"></td>';
                    }
                }
                $idGen++;
            }
            echo '<td rowspan="2" id="sumH'.$y.'" style="text-align: center;">-h</td></tr>';
            echo '<tr>';
            for($x = 1; $x <= $days; $x++){
                if($zmiany[$y][$x] == "1"){
                    $tempDate = mktime(0, 0, 0, (int)$monthChoosen, $x, (int)$yearChoosen);
                    if(date("D", $tempDate) == 'Sat' || date("D", $tempDate) == 'Sun'){
                                echo '<td style="filter: brightness(60%);" id="'.$idGen.'" onclick="change('.$idGen.')"></td>';
                    }else{
                        echo '<td id="'.$idGen.'" onclick="change('.$idGen.')"></td>';
                    }
                }else if($zmiany[$y][$x] == "2"){
                    $tempDate = mktime(0, 0, 0, (int)$monthChoosen, $x, (int)$yearChoosen);
                    if(date("D", $tempDate) == 'Sat' || date("D", $tempDate) == 'Sun'){
                                echo '<td style="filter: brightness(60%);" id="'.$idGen.'" onclick="change('.$idGen.')">12p</td>';
                    }else{
                        echo '<td id="'.$idGen.'" onclick="change('.$idGen.')">12p</td>';
                    }
                    $iloscH += 12;
                }else{
                    $tempDate = mktime(0, 0, 0, (int)$monthChoosen, $x, (int)$yearChoosen);
                    if(date("D", $tempDate) == 'Sat' || date("D", $tempDate) == 'Sun'){
                                echo '<td style="filter: brightness(60%);" id="'.$idGen.'" onclick="change('.$idGen.')"></td>';
                    }else{
                        echo '<td id="'.$idGen.'" onclick="change('.$idGen.')"></td>';
                    }
                }  
                $idGen++;          
            }
            echo '<script>document.getElementById("sumH'.$y.'").innerText = "'.$iloscH.'h"</script>';
            echo '</tr>';
        }
        echo '<tr><td colspan="'.($days + 1).'" style="text-align: right;">Łączna liczba pokrytych godzin:</td><td id="lacznieH">'.(24 * $days).'/'.(24 * $days).'</td></tr>';

        echo '</table>stworzone za pomocą algorytmu autorstwa: Mateusz Błażejczyk
        </div><br><button onclick='. "'". 'window.open("index.php","_self")'. "'" .'>Od nowa!</button><br>
        <button onclick="PrintTable()">Drukuj ten grafik</button>';
    }
}else{
    echo '
<form action="index.php" method="get">
Ilość osób: <input type="text" name="ilOs" required><br>
Jaki miesiąc: <input type="month" name="mies" required><br>
<input type="submit" value="dodaj niedyspozycje">
</form>
<div style="position:absolute;
   bottom:0;
left: 0;
   width:100%;
   height:60px;
    background-color: black;">
    <div style="width: 50%; float: left;"><a href="https://yellowsink.pl/" style="width: 50%; color: white;">Yellowsink.pl</a></div>
<div style="width: 50%; float: left; text-align: right;"><a href="https://yellowsink.pl/privacypolicy.html" style="color: white;">PrivacyPolicy</a></div>
</div>
';
}
?>
</body>
</html>
