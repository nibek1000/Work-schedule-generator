<html>
<head>
<meta charset="utf-8" >
<title>Schedule generator</title>
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
        printWindow.document.write('<html><head><title>Schedule generator</title>');
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
</script>
</head>
<body>
<?php
if(!empty($_GET["ilOs"]) || !empty($_POST["0|i"])){
    if(empty($_POST)){
        $monthChoosen = substr($_GET["mies"], 5);
        $yearChoosen = substr($_GET["mies"], 0, 4);
        $choosenDate = date("j-m-y", mktime(0, 0, 0, (int)$monthChoosen, date("d"), (int)$yearChoosen));
        
        $days = date("t", strtotime($choosenDate));

        $cspan = $days+1;
        echo '<table border="1px" id="tabela"><tr>';
        echo '<td colspan="'.$cspan.'" style="text-align: center;">'. date("F", strtotime($choosenDate)) . '</td></tr><tr>';
        
        echo '<td>Who</td>';
        for($x = 1; $x <= $days; $x++){
            if(date("N", strtotime($choosenDate. ' + '. ($x+1) . ' days')) >= 6){
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
                if(date("N", strtotime($choosenDate. ' + '. ($x+1) . ' days')) >= 6){
                    echo '<td style="filter: brightness(60%);"><input type="checkbox" name="'.$y.'|'.$x.'|1"></td>';
                }else{
                    echo '<td><input type="checkbox" name="'.$y.'|'.$x.'|1"></td>';
                }
            }
            echo '</tr>';
            echo '<tr>';
            for($x = 1; $x <= $days; $x++){
                if(date("N", strtotime($choosenDate. ' + '. ($x+1) . ' days')) >= 6){
                    echo '<td style="filter: brightness(60%);"><input type="checkbox" name="'.$y.'|'.$x.'|2"></td>';
                }else{
                    echo '<td><input type="checkbox" name="'.$y.'|'.$x.'|2"></td>';
                }
            }
            echo '</tr>';
        }
        

        echo '</table><input name="ilo" type="text" value="'.$ilOs.'" style="display: none;">';
        echo '<input type="submit" name="submit" value="Generate schedule" style="width: 100%;">';
        echo '</form><br><button onclick='. "'". 'window.open("index.php","_self")'. "'" .'>Go back!</button>';

    }else{
        $monthChoosen = substr($_GET["mies"], 5);
        $yearChoosen = substr($_GET["mies"], 0, 4);
        $choosenDate = date("j-m-y", mktime(0, 0, 0, (int)$monthChoosen, date("d"), (int)$yearChoosen));
        $days = date("t", strtotime($choosenDate));

        $cspan = $days+1;
        echo '<div id="tabelka"><table border="1px" id="tabelaF"><tr>';
        echo '<td colspan="'.$cspan.'" style="text-align: center;">'. date("F", strtotime($choosenDate)) . '</td></tr><tr>';
        
        echo '<td>Who</td>';
        for($x = 1; $x <= $days; $x++){
            if(date("N", strtotime($choosenDate. ' + '. ($x+1) . ' days')) >= 6){
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
                echo '<script>alert("Could not create schedule");</script>';
                echo '<script>alert("This message is generated when it takes too long to generate a schedule");</script>';
                echo '<script>alert("It may be not enough people to cover the schedule");</script>';
                break;
            }
        }

        for($y = 0; $y <= $iloscOsob; $y++){
            echo '<tr><td rowspan="2">'. $zmiany[$y][0] .'</td>';
            for($x = 1; $x <= $days; $x++){
                if($zmiany[$y][$x] == "1"){
                    if(date("N", strtotime($choosenDate. ' + '. ($x+1) . ' days')) >= 6){
                        echo '<td style="filter: brightness(60%);">12p</td>';
                    }else{
                        echo '<td>12p</td>';
                    }
                }else if($zmiany[$y][$x] == "2"){
                    if(date("N", strtotime($choosenDate. ' + '. ($x+1) . ' days')) >= 6){
                        echo '<td style="filter: brightness(60%);"></td>';
                    }else{
                        echo '<td></td>';
                    }
                }else{
                    if(date("N", strtotime($choosenDate. ' + '. ($x+1) . ' days')) >= 6){
                        echo '<td style="filter: brightness(60%);"></td>';
                    }else{
                        echo '<td></td>';
                    }
                }
            }
            echo '</tr>';
            echo '<tr>';
            for($x = 1; $x <= $days; $x++){
                if($zmiany[$y][$x] == "1"){
                    if(date("N", strtotime($choosenDate. ' + '. ($x+1) . ' days')) >= 6){
                        echo '<td style="filter: brightness(60%);"></td>';
                    }else{
                        echo '<td></td>';
                    }
                }else if($zmiany[$y][$x] == "2"){
                    if(date("N", strtotime($choosenDate. ' + '. ($x+1) . ' days')) >= 6){
                        echo '<td style="filter: brightness(60%);">12p</td>';
                    }else{
                        echo '<td>12p</td>';
                    }
                }else{
                    if(date("N", strtotime($choosenDate. ' + '. ($x+1) . ' days')) >= 6){
                        echo '<td style="filter: brightness(60%);"></td>';
                    }else{
                        echo '<td></td>';
                    }
                }            
            }
            echo '</tr>';
        }
        echo '</table>generated with algorithm made by: Mateusz Błażejczyk
        </div><br><button onclick='. "'". 'window.open("index.php","_self")'. "'" .'>Create new!</button><br>
        <button onclick="PrintTable()">Print this schedule</button>';
    }
}else{
    echo '
<form action="index.php" method="get">
How many people: <input type="text" name="ilOs" required><br>
Month: <input type="month" name="mies" required><br>
<input type="submit" value="add indispositions">
</form>
';
}
?>
</body>
</html>
