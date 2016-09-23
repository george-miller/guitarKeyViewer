
<?php
$error = '';

function queryIntegerGetVariable($key, $defaultValue){
    if (array_key_exists($key, $_GET)){
        return intval($_GET[$key]);
    } else {
        return $defaultValue;
    }
}

function findCurrentScaleNote($startingValue, $notesInScale) {
    for ($i = 0; $i < sizeof($notesInScale); $i++) {
        if ($notesInScale[$i] >= $startingValue) {
            return $i;
        }
    }
}

$rows = queryIntegerGetVariable('rows', 18);
$columns = queryIntegerGetVariable('columns', 6);

// Values of the chromatic scale start at 1
$startingValues = array(8, 1, 6, 11, 3, 8);
for ($i = 0; $i < $columns; $i++) {
    array_push($startingValues, queryIntegerGetVariable('startingValue' . (string) $i, $startingValues[$i]));
}

$keyType = array_key_exists('keyType', $_GET) ? $_GET['keyType'] : 'major';
$chromaticScaleLength = 12;
$notesInScale = null;
switch ($keyType){
// If a note in the chromatic scale was one unit, 
// these lengths would describe how to achive the
// major scale from distances between notesInScale. 
// Whole Whole Half Whole Whole Whole Half
    case 'major':
        $notesInScale = [1, 3, 5, 6, 8, 10, 12];
        break;
    case 'minor':
        $notesInScale = [1, 3, 4, 6, 8, 9, 11];
        break;
}    

$fretMatrix = array();
for ($i = 0; $i < $columns; $i++) {
    array_push($fretMatrix, array());
    for ($j = 0; $j < $rows; $j++) {
        array_push($fretMatrix[$i], 0);
    }
}

for ($col = 0; $col < $columns; $col++) {
    $currentChromaticNote = $startingValues[$col];
    $currentScaleNote = findCurrentScaleNote($currentChromaticNote, $notesInScale);
    for ($row = 0; $row < $rows; $row++) {
        if ($currentChromaticNote == $notesInScale[$currentScaleNote]) {
            $fretMatrix[$col][$row] = $currentScaleNote + 1;
            $currentScaleNote++;
            if ($currentScaleNote == sizeof($notesInScale)) {
                $currentScaleNote = 0;
            }
        }
        $currentChromaticNote++;
        if ($currentChromaticNote > $chromaticScaleLength) {
            $currentChromaticNote = 1;
        }
    }
}

?>


<!DOCTYPE html>
<html>
<head>
<style>
.col {
    float: left;
}
.row {
    font-family: 'courier';
    text-align: center;
    font-weight: bold;
    border:solid 1px;
    height:30px;
    width:30px;
    padding-top: 10px;
}
</style>
</head>
<body>

<?php for ($col = 0; $col < $columns; $col++) : ?>

<div class='col'>

    <?php for ($row = 0; $row < $rows; $row++) : ?>

    <div class='row'>
        <?php if ($fretMatrix[$col][$row] != 0) {print($fretMatrix[$col][$row]);} ?>
    </div>

    <?php endfor; ?>

</div>

<?php endfor; ?>

</body>
</html>


