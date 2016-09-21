
<?php
$error = '';

function queryGetVariable($key, $defaultValue){
    if ($_GET[$key]){
        return intval($_GET[$key]);
    } else {
        return $defaultValue;
    }
}

$rows = queryGetVariable('rows', 18);
$columns = queryGetVariable('columns', 6);
$startingValues = array();
for ($i = 0; $i < $columns; $i++) {
    array_push($startingValues, queryGetVariable('startingValue' . (string) $i, 1));
}

$keyType = 'major';
$chromaticScaleLength = 12;
$stepLengths = null;
switch ($keyType){
// If a note in the chromatic scale was one unit, 
// these lengths would describe how to achive the
// major scale from distances between notes. 
// Whole Whole Half Whole Whole Whole Half
    case 'major':
        $stepLengths = [2, 2, 1, 2, 2, 2, 1];
        break;
    case 'minor':
        $stepLengths = [2, 1, 2, 2, 1, 2, 2];
        break;
}    

$fretMatrix = array();
for ($i = 0; $i < $columns; $i++) {
    array_push($fretMatrix, array());
    for ($j = 0; $j < $rows; $j++) {
        array_push($fretMatrix[$i], 0);
    }
}

print_r($fretMatrix);
for ($col = 0; $col < $columns; $columns++) {
    array_push($fretMatrix, array());
    for ($row = 0; $row < $rows; $row++) {
        
        $stepLength = $stepLengths[$i];
    }
}

?>
