
<?php
$error = '';

function queryIntegerGetVariable($key, $defaultValue){
    if (array_key_exists($key, $_GET) && is_numeric($_GET[$key])){
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

$rows = queryIntegerGetVariable('rows', 14);
$columns = queryIntegerGetVariable('columns', 6);
$noteLetters = array('A', 'A#', 'B', 'C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#');

// Values of the chromatic scale start at 1
$startingValues = array(8, 1, 6, 11, 3, 8);
for ($i = 0; $i < $columns; $i++) {
    $startingValues[$i] = queryIntegerGetVariable('startingValue' . (string) $i, $startingValues[$i]);
}

$keyType = array_key_exists('keyType', $_GET) ? $_GET['keyType'] : 'major';
$chromaticScaleLength = 12;
$notesInScale = array();
$keyTypes = array('major', 'minor', 'manual');
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
 case 'manual':
   for ($i = 0; $i < 7; $i++) {
     array_push($notesInScale, queryIntegerGetVariable('noteInScale' . (string) $i, 1));
   }
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
.row {
 display: block;
 }
.col {
  float: left;
  font-family: 'courier';
  text-align: center;
  font-weight: bold;
 border:solid 1px;
 height:40px;
 width:40px;
  line-height: 40px;
 }
.verticalCont {
 margin: 10px;
  float: left;
 }
.inScale {
  background-color: #DDD;
 }
body {
 height:100%;
}
</style>
</head>
<body>

<div class="verticalCont">

<?php for ($row = 0; $row < $rows; $row++) : ?>

<div class='row'>
    <?php for ($col = 0; $col < $columns; $col++) : ?>

    <div class='col<?php if ($fretMatrix[$col][$row] != 0) {print( ' inScale');} ?>'>
        <?php if ($fretMatrix[$col][$row] != 0) {print($fretMatrix[$col][$row]);} ?>
    </div>

    <?php endfor; ?>


</div>

<?php endfor; ?>
</div>
<div class="verticalCont" style="width:60%;">
<h1>Gutiar Key Viewer</h1>
          <p>This page is degisned for guitarists to see all the availible notes in a key to help visualize the whole neck of the guitar.  The grid is the fretboard, and with notes increasing in pitch as we go down the grid.  Scale notes in the grid are shown from 1 to 7, representing the seven notes in every regular scale.  The spaces between said notes are determined by the key type (i.e. major or minor).
          </p>
          <p>For example, if we consider the chromatic scale as notes going from 1 to 12, the major scale has notes 1, 3, 5, 6, 8, 10, 12, and back to 13 (which is the same as 1, just an octave higher).  The algorithm for creating the grid uses a list of numbers like that to identify all notes in the key type all the way up the neck, starting at the values given (defaulted to standard guitar tuning).
          </p>
         <p>
          You can input your own scale, change the starting values of each column to work with different guitar string tunings, and change the amount of rows and columns to see more frets, or more strings.  Hopefully this helps you to become a better guitar player!</p>


<h2>Configuration Options</h2>

<form>
<label>Select the type of scale
    <select name="keyType">
        <?php foreach($keyTypes as $type): ?>
        <option <?php if ($type == $keyType) { print('selected="selected"'); }  ?> ><?php print($type)?></option>
        <?php endforeach; ?>
    </select>
</label>
<br/>
<p>Or select the notes in the scale yourself</p>
  <?php for($i = 0; $i < sizeof($notesInScale); $i++): ?>
<label> Note <?php print($i+1);?>
<input type="text" name="noteInScale<?php print($i);?>" placeholder="<?php print($notesInScale[$i]); ?>" />
</label>
  <br/>
  <?php endfor; ?>
<p>Indicate the starting value (as a number indicating the note of the chromatic scale, from 1 to 12) for each column</p>
<?php for($i = 0; $i < $columns; $i++): ?>
<label>
    Column <?php print($i+1); ?>
    <input type="text" name="startingValue<?php print($i); ?>" placeholder="<?php print($startingValues[$i])?>">
</label>
<br/>
<?php endfor; ?>
<label>
          Specify the number of columns: <input type="text" name="columns" placeholder="<?php print($columns)?>">
</label>
          <br/>
<label>
          Specify the number of rows: <input type="text" name="rows" placeholder="<?php print($rows)?>">
</label>
          <br/>
<input type='submit'>
</form>
</div>

</body>
</html>


