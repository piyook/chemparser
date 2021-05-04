<?php
namespace App\ChemSymb;

/**
 * Logic used for formatting a string containing a molecular or empirical chemical formula
 * to formatted HTML
 */
abstract class AbstractTests {
    
    /**
     * test to check if the blank spaces in the input string should be removed
     * or kept if they are next to an arrow
     * 
     * @param  mixed $index
     * @param  mixed $element
     * @param  mixed $formulaArray
     * @return bool
     */
    protected function test_if_blank_space_to_be_removed_between_characters($index, $element, $formulaArray){

        return preg_match('/[ ]/', $element) 
        && (preg_match('/[0-9A-Z]/', $formulaArray[$index-1]))
        && !(preg_match('/[<=>]/', $formulaArray[$index+1]))
        ;
    
    }
        
    /**
     * test if the number in the input string represents an ionic charge value
     * greater than one
     * 
     * @param  mixed $index
     * @param  mixed $element
     * @param  mixed $formulaArray
     * @return bool
     */
    protected function test_if_charge_number_greater_than_one_that_should_be_superscript($index, $element, $formulaArray){
    
        return preg_match('/[0-9n]/', $element) && (preg_match('/[+-]/', $formulaArray[$index+1]));
    }
        
    /**
     * test if the number in the input string represents a single ionic charge value
     * 
     *
     * @param  mixed $index
     * @param  mixed $element
     * @param  mixed $formulaArray
     * @return bool
     */
    protected function test_if_a_single_charge_that_should_be_superscript($index, $element, $formulaArray){
    
        return preg_match('/[+-]/', $element) && (preg_match('/[A-Z\]]/', $formulaArray[$index-1]));
    }
        
    /**
     * test if the double digit number or letter n [ as in (2n+1) ] in the input string is a subscript 
     *
     * @param  mixed $index
     * @param  mixed $element
     * @param  mixed $formulaArray
     * @return bool
     */
    protected function test_if_double_digit_number_or_n_next_to_element_should_be_subscript($index, $element, $formulaArray){
    
        return preg_match('/[0-9n]/', $element) 
        && (preg_match('/[A-Z)]/', $formulaArray[$index-1]))
        && (preg_match('/[0-9]/', $formulaArray[$index+1]));
    }
        
    /**
     * test if the single digit number or letter n [ as in (n+1) ] in the input string is a subscript 
     *
     * @param  mixed $index
     * @param  mixed $element
     * @param  mixed $formulaArray
     * @return bool
     */
    protected function test_if_single_digit_number_or_n_should_be_subscript($index, $element, $formulaArray){
    
        return preg_match('/[0-9n]/', $element) 
        && (preg_match('/[A-Z\])]/', $formulaArray[$index-1]))
        && !preg_match('/[n]/', $formulaArray[$index-1])
        ;
    }
        
    /**
     * test if a bracket is present in the input string and is to be further evaluated
     *
     * @param  mixed $element
     * @return bool
     */
    protected function check_if_bracket_is_present($element){
    
        return $element === '(';
    } 
        
    /**
     * test if the bracket is part of a state symbol 
     * [ E.g (g) for gas, (l) for liquid or (s) for solid) ]
     *
     * @param  mixed $formulaArray
     * @param  mixed $index
     * @return bool
     */
    protected function check_if_this_is_a_state_bracket($formulaArray, $index){
        return preg_match('/[gls]/', $formulaArray[$index+1]);
    }
        
    /**
     * test if the bracket is part of a state symbol 
     *  for aqueous [ E.g ]
     *
     * @param  mixed $formulaArray
     * @param  mixed $index
     * @return bool
     */
    protected function check_if_this_is_a_aq_state_bracket($formulaArray, $index){
        return preg_match('/[aq]/', $formulaArray[$index+1]);
    }
        
    /**
     *  Check if the input string contains <=> to represent an equilibrium arrow
     *
     * @param  mixed $index
     * @param  mixed $element
     * @param  mixed $formulaArray
     * @return bool
     */
    protected function check_if_equilibrium_arrow($index, $element, $formulaArray){
    
        return $element === '=' 
        && $formulaArray[$index-1] === '<' 
        && $formulaArray[$index+1] === '>'
        ;
    }
        
    /**
     *  Check if the input string contains => to represent a forward arrow
     *
     * @param  mixed $index
     * @param  mixed $element
     * @param  mixed $formulaArray
     * @return bool
     */
    protected function check_if_forward_arrow($index, $element, $formulaArray){
    
        return $element === '=' 
        && $formulaArray[$index+1] === '>'
        ;
    }
        
    /**
     *  Check if the input string contains <= to represent a backward arrow
     *
     * @param  mixed $index
     * @param  mixed $element
     * @param  mixed $formulaArray
     * @return bool
     */
    protected function check_if_backwards_arrow($index, $element, $formulaArray){
    
        return $element === '=' 
        && $formulaArray[$index-1] === '<'
        ;
    }
    
    /**
     * filters input string to allowed characters only to include < > which are used for arrows
     * characaters are split into an array
     * @param  mixed $string
     * @return void
     */
    protected function extract_allowed_characters_to_array($string){

        preg_match_all('/[A-Za-z0-9+-.<=>()\s\[\]]/', $string, $matches);
        
        array_unshift($matches[0], "^");
        $matches[0][] = "$";

        return $matches[0];
    }
    
    /**
     * if the character in the array is allowed but is not part of an element check
     * if it is still allowed E.g if its a number or part of a state (g)
     *
     * @param  mixed $letter
     * @return void
     */
    protected function check_if_non_element_character_is_allowed($letter){
    
        return preg_match('/[0-9+-.<=>qsg()^$\s\[\]n]/', $letter);
    }
    
    /**
     * If a character is also part of an element name then check if its actually part
     * of a state symbol to avoid errors E.g Na and aq or Al and (l)
     *
     * @param  mixed $letter
     * @return void
     */
    protected function check_if_element_character_is_part_of_state_symbol($letter){
    
        return preg_match('/[(al)]/', $letter);
    }
    
    /**
     * Check if the element has two letters not one E.g Br, Al, Mg etc.,
     * If this is the case then a Capital Letter should be followed by lower case
     *
     * @param  mixed $index
     * @param  mixed $formulaString
     * @return void
     */
    protected function check_if_two_letter_element_character($index, $formulaString){
    
        return ctype_lower($formulaString[$index+1]) && $formulaString[$index+1] != 'n';
    }
}