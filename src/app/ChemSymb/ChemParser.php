<?php

namespace App\ChemSymb;

use App\ChemSymb\AbstractTests;

class ChemParser extends AbstractTests{

    public array $periodic_table;

    public function __construct(){

        $this->periodic_table = ['H','He',
            'Li','Be','B','C','N','O','F','Ne',
            'Na','Mg','Al','Si','P','S','Cl','Ar',
            'K','Ca','Sc','Ti','V','Cr','Mn','Fe','Co','Ni','Cu','Zn','Ga','Ge','As','Se','Br','Kr',
            'Rb','Sr','Y','Zr','Nb','Mo','Tc','Ru','Rh','Pd','Ag','Cd','In','Sn','Sb','Te','I','Xe',
            'Cs','Ba','La','Hf','Ta','W','Re','Os','Ir','Pt','Au','Hg','Tl','Pb','Bi','Po','At','Rn',
            'Fr','Ra','Ac','Rf','Db','Sg','Bh','Hs','Mt','Ds','Rg','Cn','Nh','Fl','Mc','Lv','Ts','Og',
            'Ce','Pr','Nd','Pm','Sm','Eu','Gd','Tb','Dy','Ho','Er','Tm','Yb','Lu',
            'Th','Pa','U','Np','Pu','Am','Cm','Bk','Cf','Es','Fm','Md','No','Lr'
        ]; 
        
    }


    public function analyse($string){

        preg_match_all('/[A-Za-z0-9+-.<=>()\s\[\]]/', $string, $matches);

        array_unshift($matches[0], "^");
        $matches[0][] = "$";

        // return $matches[0];

        return $this->matchElements($matches[0]);
    }

    protected function matchElements($formulaString){

        $resultsArray = array();

        foreach($formulaString as $index => $letter) {

            if (preg_match('/[0-9+-.<=>qsg()^$\s\[\]n]/', $letter) ) {
                $resultsArray[]=$letter;
                continue;
            }

            if (preg_match('/[(al)]/', $letter) ) {
                if ($formulaString[$index-1]==='('){
                $resultsArray[]=$letter;
                }
                continue;
            }
            
            if (ctype_upper($letter)){

                    if (ctype_lower($formulaString[$index+1]) && $formulaString[$index+1] != 'n'){

                            if ($this->check_is_element($letter.$formulaString[$index+1]))
                            {$resultsArray[] = $letter.$formulaString[$index+1];
                            }
                        
                    } else {
                        if ($this->check_is_element($letter)){
                        $resultsArray[] = $letter;
                        }
                    }
            }

        }
      
        return $resultsArray;
        
    }

    protected function check_is_element($element){

        return in_array($element, $this->periodic_table);
        
    }

    public function HTMLformat($formulaArray){

        foreach ($formulaArray as $index=>$element) {

                    switch($element){

                        case $this->test_if_blank_space_to_be_removed_between_characters($index, $element, $formulaArray):
                            $formulaArray[$index] = '';
                            break;

                        case $this->test_if_charge_number_greater_than_one_that_should_be_superscript($index, $element, $formulaArray):
                            
                            $formulaArray[$index] = '<sup>'.$element.$formulaArray[$index+1].'</sup>';
                            $formulaArray[$index+1]='';
                            break;

                        case $this->test_if_a_single_charge_that_should_be_superscript($index, $element, $formulaArray):

                            $formulaArray[$index] = '<sup>'.$element.'</sup>';
                            break;
                        
                        // subscript numbers H2 and H2O or (H20)2

                        case $this->test_if_double_digit_number_or_n_next_to_element_should_be_subscript($index, $element, $formulaArray):

                            $formulaArray[$index] = '<sub>'.$element.$formulaArray[$index+1].'</sub>';
                            $formulaArray[$index+1] = '';
                            break;

                        case $this->test_if_single_digit_number_or_n_should_be_subscript($index, $element, $formulaArray):
                            $formulaArray[$index] = '<sub>'.$element.'</sub>';

                            break;
                    
                        // (aq),(g),(l) ..
                        case $this->check_if_bracket_is_a_state_or_just_a_bracket($element):  
                            
                              $number_to_null=0;

                            if ($str = $this->bracketFormulaCheck($formulaArray, $index)){
                                $formulaArray[$index] = $str[0];
                                  $number_to_null=$str[1];
                            };

            
                            if ($this->check_if_this_is_a_state_bracket($formulaArray, $index)) {

                             $formulaArray[$index]="<sub>(".$formulaArray[$index+1].")</sub>";
                               $number_to_null = 2;
                            }

                            if ($this->check_if_this_is_a_aq_state_bracket($formulaArray, $index)) {

                                $formulaArray[$index]="<sub>(aq)</sub>";
                                  $number_to_null=3;
                               }

                            $formulaArray = 
                            $this->set_obsolete_array_items_to_null($index, $formulaArray,  $number_to_null);

                            break;

        
                            case $this->check_if_equilibrium_arrow($index, $element, $formulaArray):
                            
                                $formulaArray[$index-1]=""; 
                                $formulaArray[$index]="&#8652;";
                                $formulaArray[$index+1]="";
                                break;

                            case $this->check_if_forward_arrow($index, $element, $formulaArray):
             
                                $formulaArray[$index]="&#8594;";
                                $formulaArray[$index+1]="";
                                break;

                            case $this->check_if_backwards_arrow($index, $element, $formulaArray):
                     
                                $formulaArray[$index]="&#8592;";
                                $formulaArray[$index-1]="";
                                break;
                        
                        default: 
                            break;

                    }
        }
            return $this->cleanUp($formulaArray);
    }


protected function cleanUp($formulaArray) {

        $formulaArray[0]='';
        $formulaArray[count($formulaArray)-1]='';

        $result = implode("",$formulaArray);

        
        return $result;

}

protected function bracketFormulaCheck($formulaArray, $index){

    $arrayLength = count($formulaArray)-1;
    $flag = 0;
    $startPos = $index;
    $endPos = $index;
    $returnString="<sub>";

    //work out whether bracket is of type(2n+1)

    while (($formulaArray[$index] != ')' && ($index <= $arrayLength))){
        if ($formulaArray[$index] === 'n'){

            $flag=1;
        }
        $index++;
        $endPos++;
        
    }
    // if it is and flag have a value then create a new string to be subbed
    if ($flag){

            for ($x=$startPos;$x<=$endPos;$x++){
                
            $returnString .= $formulaArray[$x];
            $formulaArray[$x]='';
            }
            $returnString .="</sub>";

    }

    if ($flag) {
        return [$returnString, $endPos-$startPos];
      
    } else { return false; }
}

protected function set_obsolete_array_items_to_null($index, $formulaArray,  $number_to_null){

    for ($x=1; $x <=   $number_to_null; $x++){
        $formulaArray[$index+$x]='';
       }
       return $formulaArray;
}



}

