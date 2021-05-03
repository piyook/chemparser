<?php

require __DIR__.'/../app/bootstrap.php';

echo "<h1 style='text-align:center'> Chemical Symbol Utility Class </h1>";

echo ($ParserController->getFormula('4(3H2O)2(g) <=> 6H+(aq) + 3O2-(aq) + H2(g)'));

echo("<br><br>");

echo ($ParserController->getFormula(('2[B12H12]2-(aq)')));

echo ("<br><br>");

$testString=' => [PtCl3(C2H4)]-  <=';
echo "<br>test string is : $testString</br>";

echo ($ParserController->getFormula(($testString)));

echo('<br><br> ------------------- <br><br>');

$testString2='H2 => 2H+ + O2-';
echo "<br>test string is : $testString2</br>";

echo ($ParserController->getFormula(($testString2)));

?>