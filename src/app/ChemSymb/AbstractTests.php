<?php
namespace App\ChemSymb;

abstract class AbstractTests {

    protected function test_if_blank_space_to_be_removed_between_characters($index, $element, $formulaArray){

        return preg_match('/[ ]/', $element) 
        && (preg_match('/[0-9A-Z]/', $formulaArray[$index-1]))
        && !(preg_match('/[<=>]/', $formulaArray[$index+1]))
        ;
    
    }
    
    protected function test_if_charge_number_greater_than_one_that_should_be_superscript($index, $element, $formulaArray){
    
        return preg_match('/[0-9n]/', $element) && (preg_match('/[+-]/', $formulaArray[$index+1]));
    }
    
    protected function test_if_a_single_charge_that_should_be_superscript($index, $element, $formulaArray){
    
        return preg_match('/[+-]/', $element) && (preg_match('/[A-Z\]]/', $formulaArray[$index-1]));
    }
    
    protected function test_if_double_digit_number_or_n_next_to_element_should_be_subscript($index, $element, $formulaArray){
    
        return preg_match('/[0-9n]/', $element) 
        && (preg_match('/[A-Z)]/', $formulaArray[$index-1]))
        && (preg_match('/[0-9]/', $formulaArray[$index+1]));
    }
    
    protected function test_if_single_digit_number_or_n_should_be_subscript($index, $element, $formulaArray){
    
        return preg_match('/[0-9n]/', $element) 
        && (preg_match('/[A-Z\])]/', $formulaArray[$index-1]))
        && !preg_match('/[n]/', $formulaArray[$index-1])
        ;
    }
    
    protected function check_if_bracket_is_a_state_or_just_a_bracket($element){
    
        return $element === '(';
    } 
    
    protected function check_if_this_is_a_state_bracket($formulaArray, $index){
        return preg_match('/[gls]/', $formulaArray[$index+1]);
    }
    
    protected function check_if_this_is_a_aq_state_bracket($formulaArray, $index){
        return preg_match('/[aq]/', $formulaArray[$index+1]);
    }
    
    protected function check_if_equilibrium_arrow($index, $element, $formulaArray){
    
        return $element === '=' 
        && $formulaArray[$index-1] === '<' 
        && $formulaArray[$index+1] === '>'
        ;
    }
    
    protected function check_if_forward_arrow($index, $element, $formulaArray){
    
        return $element === '=' 
        && $formulaArray[$index+1] === '>'
        ;
    }
    
    protected function check_if_backwards_arrow($index, $element, $formulaArray){
    
        return $element === '=' 
        && $formulaArray[$index-1] === '<'
        ;
    }
}