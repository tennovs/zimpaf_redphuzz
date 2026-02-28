<?php
    $maxcounter = isset($_GET['maxcounter']) ? (int)$_GET['maxcounter'] : 0;
    $total=0;
    for ($i = 1; $i <= $maxcounter; $i++) {
        add($i);
    }
    function add($number) {
        global $total;
        $total += $number;
    }
    // Output result
    echo "Max counter: $maxcounter\n";
    echo "Total sum: $total\n";
?>


