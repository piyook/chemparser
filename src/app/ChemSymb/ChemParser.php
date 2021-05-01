<?php

namespace App\ChemSymb;

class ChemParser {

    public array $periodic_table;

    public function __construct(){

        $this->periodic_table = ['H','He',
            'Li','Be','B','C','N','O','F','Ne',
            'Na','Mg','Al','Si','P','S','Cl','Ar',
            'K','Ca','Sc','Ti','V','Cr','Mn','Fe','Co','Ni','Cu','Zn','Ga','Ge','As','Se','Br','Kr'
        ]; 
        
    }

   

    /*  1 sanitise input - strip tags etc., make sure the string then contains only allowed chars
            2 validate input - make sure its only elements
            3 convert string - 
                a. convert elements to capitals
                b. convert numbers to subscripts
                c. convert numbers plus charge to superscript
                d. convert charge alone to superscript
                e. convert decimail to free radical
                f. convert => to arrow
                g convert <=> to reversibe arrow
                h convert state e.g (aq) or (g) to subscript
            4. Return html fragment with styling for insertion into page
                */

    public function getFormula(string $string) : string {

        $formulaArray = $this->analyse(strval(trim($string)));

        $formulaArray=$this->matchElements($formulaArray);
    
        return $this->HTMLFormat($formulaArray);

    }

    private function analyse($string){

        preg_match_all('/[A-Za-z0-9+-.<=>()\s]/', $string, $matches);

        array_unshift($matches[0], "^");
        $matches[0][] = "$";

        return $matches[0];
    }

    private function matchElements($formulaString){

        $resultsArray = array();

        foreach($formulaString as $index => $letter) {

            if (preg_match('/[0-9+-.<=>(aqgsl)^$\s]/', $letter) ) {
                $resultsArray[]=$letter;
                continue;
            }
            
            if (ctype_upper($letter)){

                    if (ctype_lower($formulaString[$index+1])){

                        if ($this->check_is_element($letter.$formulaString[$index+1]))
                            {$resultsArray[] = $letter.$formulaString[$index+1];}
                    } else {
                        if ($this->check_is_element($letter)){
                        $resultsArray[] = $letter;
                        }
                    }
            }

        }
      
        return $resultsArray;
        
    }

    private function check_is_element($element){

        return in_array($element, $this->periodic_table);
        
    }

    private function HTMLformat($formulaArray){

        foreach ($formulaArray as $index=>$element) {

                    switch($element){

                        // E.g 2- or 2+
                        case (preg_match('/[0-9]/', $element) && (preg_match('/[+-]/', $formulaArray[$index+1]))):
                            
                            $formulaArray[$index] = '<sup>'.$element.$formulaArray[$index+1].'</sup>';
                            // unset($formulaArray[$index+1]);
                            $formulaArray[$index+1]='';
                            break;

                        // E.g + or -
                        case (preg_match('/[+-]/', $element) && (preg_match('/[A-Z]/', $formulaArray[$index-1]))):

                            $formulaArray[$index] = '<sup>'.$element.'</sup>';
                            break;
                        
                        // H2 and H2O or (H20)2
                        case (preg_match('/[0-9]/', $element) 
                        && (preg_match('/[A-Z)]/', $formulaArray[$index-1]))
                        ):
                            
                            $formulaArray[$index] = '<sub>'.$element.'</sub>';
                            break;
                    
                        // (aq),(g),(l) ..
                        case ($element === '('):  
                            // $formulaArray[$index] = '<sub>'.$element.'</sub>';
                            $unset=0;
                            if (preg_match('/[gls]/', $formulaArray[$index+1])) {

                             $formulaArray[$index]="<sub>(".$formulaArray[$index+1].")</sub>";
                             $unset = 2;
                            }

                            if (preg_match('/[aq]/', $formulaArray[$index+1])) {

                                $formulaArray[$index]="<sub>(aq)</sub>";
                                $unset=3;
                               }

                            for ($x=1; $x <= $unset; $x++){
                                $formulaArray[$index+$x]='';
                               }
                             
                              
                            break;

                            // <=> equals &#8652;
                            // => equals &#8594;
                            // <= equals &#8592;

                            case ($element === '=' 
                            && $formulaArray[$index-1] === '<' 
                            && $formulaArray[$index+1] === '>'):
                            $formulaArray[$index-1]=""; 
                            $formulaArray[$index]="&#8652;";
                            $formulaArray[$index+1]="";

                                break;

                            case ($element === '=' 
                            && $formulaArray[$index+1] === '>'):
             
                            $formulaArray[$index]="&#8594;";
                            $formulaArray[$index+1]="";
    
                                    break;

                            case ($element === '=' 
                            && $formulaArray[$index-1] === '<'):
                     
                            $formulaArray[$index]="&#8592;";
                            $formulaArray[$index-1]="";
            
                                            break;
                        
                        default: 
                            break;

                    }
        }
        
            return $this->cleanUp($formulaArray);
    }


private function cleanUp($formulaArray) {

        $formulaArray[0]='';
        $formulaArray[count($formulaArray)-1]='';

        $result = implode("",$formulaArray);

        
        return $result;

}


}

