<?php

namespace App\ChemSymb;

use App\ChemSymb\AbstractTests;

/**
 * Converts A String with unformatted Molecular or Empirical Chemical Fromulae into formatted HTML.
 */
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

    public function __invoke(string $string) : string {

        $formulaArray = $this->analyse(strval(trim($string)));
    
        return $this->HTMLFormat($formulaArray);

    }
    
    /**
     * Parses a String with unformatted Molecular or Empirical Chemical Fromulae into formatted HTML.
     *
     * @param  mixed $string
     * @return string
     */
    public function parse(string $string) : string {

        $formulaArray = $this->analyse(strval(trim($string)));
    
        return $this->HTMLFormat($formulaArray);

    }
    
    /**
     * Extracts allowed characters into an Array
     *
     * @param  mixed $string
     * @return array
     */
    protected function analyse($string){

        $formula_array = $this->extract_allowed_characters_to_array($string);

        return $this->extractElements($formula_array);
    }
    
    /**
     * Extracts Elements and allowed characters from an array and places them in new array
     *
     * @param  mixed $formulaArray
     * @return array
     */
    protected function extractElements($formulaArray){

        $resultsArray = array();

        foreach($formulaArray as $index => $letter) {

            if ($this->check_if_non_element_character_is_allowed($letter)) {
                $resultsArray[]=$letter;
                continue;
            }

            if ($this->check_if_element_character_is_part_of_state_symbol($letter) ) {
                if ($formulaArray[$index-1]==='('){
                $resultsArray[]=$letter;
                }
                continue;
            }
            
            if (ctype_upper($letter)){

                    if ($this->check_if_two_letter_element_character($index, $formulaArray)){

                            if ($this->check_is_element($letter.$formulaArray[$index+1]))
                            {$resultsArray[] = $letter.$formulaArray[$index+1];
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
    
    /**
     * Checks character or characters are an element in the periodic table
     *
     * @param  mixed $element
     * @return void
     */
    protected function check_is_element($element){

        return in_array($element, $this->periodic_table);
        
    }
    
    /**
     * Converts a string containing a chemical formula into a formatted HTML String
     *
     * @param  mixed $formulaArray
     * @return string
     */
    protected function HTMLformat($formulaArray){

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
                        case $this->check_if_bracket_is_present($element):  
                            
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

    
    /**
     * Deletes start '^' and end '$' characters that are used for processing the array
     *
     * @param  mixed $formulaArray
     * @return void
     */
    protected function cleanUp($formulaArray) {

        $formulaArray[0]='';
        $formulaArray[count($formulaArray)-1]='';

        $result = implode("",$formulaArray);

        
        return $result;

    }
    
    /**
     * checks if the bracket in the fomula is part of a state, part of a (2n+1) type or just a normal bracket
     *
     * @param  mixed $formulaArray
     * @param  mixed $index
     * @return void
     */
    protected function bracketFormulaCheck($formulaArray, $index){

        $arrayLength = count($formulaArray)-1;
        $flag = 0;
        $startPos = $index;
        $endPos = $index;
        $returnString="<sub>";


        while (($formulaArray[$index] != ')' && ($index <= $arrayLength))){
            if ($formulaArray[$index] === 'n'){

                $flag=1;
            }
            $index++;
            $endPos++;
            
        }
        
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

/**
 * Deletes obsolete characters in an array due to combination e.g '(','a','q',')' to '(aq)'
     * '(' becomes '(aq)' and 'a','q',')' need to be removed
 *
 * @param  mixed $index
 * @param  mixed $formulaArray
 * @param  mixed $number_to_null
 * @return void
 */
protected function set_obsolete_array_items_to_null($index, $formulaArray,  $number_to_null){

    for ($x=1; $x <=   $number_to_null; $x++){
        $formulaArray[$index+$x]='';
       }
    
    return $formulaArray;
}



}

