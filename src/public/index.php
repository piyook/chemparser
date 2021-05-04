<?php

require __DIR__.'/../app/bootstrap.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHEMPARSER TEST PAGE</title>
</head>
<body>

<h1 style='text-align:center'> Chemical Formula Parser </h1>
<h3 style='text-align:center'> Convert Molecular and Empirical Chemical Formula to formatted HTML</h3>

<p> Test string is:  2H2(g) + O2(g) => 2H2O(g) </p>
<p> Formatted String is : <b><?php echo $ChemParser->parse('2H2(g) + O2(g) => 2H2O(g)'); ?></b></p>
<br>

<p> Test string is:  Fe2O3(s) + 3CO(g) -> 2Fe(l) + 3CO2(g) </p>
<p> Formatted String is : <b> <?php echo $ChemParser->parse('Fe2O3(s) + 3CO(g) -> 2Fe(l) + 3CO2(g)'); ?> </b></p>
<br>

<p> Test string is:  NH4Cl(s) <=> NH3(g) + HCl(g)  </p>
<p> Formatted String is : <b> <?php echo $ChemParser->parse('NH4Cl(s) <=> NH3(g) + HCl(g)'); ?> </b></p>
<br>

<p> Test string is:  nC6H12O6 => (CH10O5)n + nH2O  </p>
<p> Formatted String is : <b> <?php echo $ChemParser->parse('nC6H12O6 => (CH10O5)n + nH2O'); ?> </b></p>
<br>

<p> Test string is:  nC6H12O6 => (CH10O5)n + nH2O  </p>
<p> Formatted String is : <b> <?php echo $ChemParser->parse('nC6H12O6 => (CH10O5)n + nH2O'); ?> </b></p>
<br>

<p> Test string is:  nCnH(2n+2) </p>
<p> Formatted String is : <b> <?php echo $ChemParser->parse('nCnH(2n+2)'); ?> </b></p>
<br>

<p> Test string is:  [Fe(H2O)6]3+ </p>
<p> Formatted String is : <b> <?php echo $ChemParser->parse('[Fe(H2O)6]3+'); ?> </b></p>
<br>

<p> Test string is:  CuSO4(s) <=> Cu2+(aq) SO42-(aq) </p>
<p> Formatted String is : <b> <?php echo $ChemParser('CuSO4(s) <=> Cu2+(aq) SO4 2- (aq)'); ?> </b></p>
<br>
</body>
</html>