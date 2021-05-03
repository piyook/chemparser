<?php

namespace App\ChemSymb;

use App\ChemSymb\AbstractTests;
use App\ChemSymb\ChemParser;

class ParserController extends AbstractTests{

    protected $ChemParser;

    public function __construct()
    {
        $this->ChemParser = new ChemParser;
    }

    public function getFormula(string $string) : string {

        $formulaArray = $this->ChemParser->analyse(strval(trim($string)));
    
        return $this->ChemParser->HTMLFormat($formulaArray);

    }

}

?>