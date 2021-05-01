<?php

require __DIR__.'/../app/bootstrap.php';

echo "<h1 style='text-align:center'> Chemical Symbol Utility Class </h1>";

echo ($ChemParser->getFormula('4(3H2O)2(g) => 6H+(aq) + 3O2-(aq) + H2(g)'));

echo ($ChemParser->getFormula('<script>alert("hi"); </script>'));

?>