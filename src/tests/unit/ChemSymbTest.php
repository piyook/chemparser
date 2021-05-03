<?php

use App\ChemSymb\ParserController;

// use function PHPUnit\Framework\assertTrue;

class ChemSymbTest extends \PHPUnit\Framework\TestCase
{


    /** @test */
    public function check_parser_returns_an_html_string(){

        $parser = new ParserController;
        $input = 'H2O';
        $return_string = $parser->getFormula($input);

        $this->assertFalse($return_string == strip_tags($return_string));

    }

    /** @test */
    public function check_parser_strips_out_non_standard_characters(){

        $parser = new ParserController;
        $input = '<script>CaCO3=>CO2 $$%££ </script>';
        $return_string = $parser->getFormula($input);

    
        $this->assertStringNotContainsString('<script>', $return_string);
        $this->assertStringNotContainsString('$$%££ ', $return_string);
    }

    /** @test */
    public function check_parser_sanitises_a_string(){

        $parser = new ParserController;
        $input = '<script> this is a test </script>';
        $return_string = $parser->getFormula($input);

    
        $this->assertStringNotContainsString('<script>', $return_string);

    }

    /** @test */
    public function check_non_elements_are_excluded_from_results(){

        $parser = new ParserController;
        $input = "CaGCO3 => CaO";
        $return_string = $parser->getformula($input);
      
        $this->assertStringNotContainsString("G", $return_string);

     }

     /** @test */
     public function check_formula_is_parsed_to_formatted_html(){

        $parser = new ParserController;
        $input = "H2 => 2H+ + O2-";
        $return_string = $parser->getformula($input);
        $expected_string="H<sub>2</sub> &#8594; 2H<sup>+</sup> + O<sup>2-</sup>";


        $this->assertStringContainsString($expected_string, $return_string);
     }

     /** @test */
     public function check_state_symbols_are_parsed_to_html(){

        $parser = new ParserController;
        $input = "CaCO3(g)";
        $return_string = $parser->getformula($input);
        $expected_string="<sub>(g)</sub>";


        $this->assertStringContainsString($expected_string, $return_string);
     }

      /** @test */
      public function check_brackets_are_parsed_to_html(){

        $parser = new ParserController;
        $input = "(2H2O)[B12H12]2-";
        $return_string = $parser->getformula($input);
    
        $this->assertStringContainsString("[", $return_string);
        $this->assertStringContainsString("]", $return_string);
        $this->assertStringContainsString("(", $return_string);
        $this->assertStringContainsString(")", $return_string);

     }

     /** @test */
     public function check_subscript_numbers_are_parsed_to_html(){

        $parser = new ParserController;
        $input = "(2H2O)[B12H12]2-";
        $return_string = $parser->getformula($input);
    
        $this->assertStringContainsString("<sub>2</sub>", $return_string);
        $this->assertStringContainsString("<sub>12</sub>", $return_string);

     }

     /** @test */
     public function check_subscript_formulas_are_parsed_to_html(){

        $parser = new ParserController;
        $input = "CnH(2n+1)OH";
        $return_string = $parser->getformula($input);
    
        $this->assertStringContainsString("<sub>n</sub>", $return_string);
        $this->assertStringContainsString("<sub>(2n+1)</sub>", $return_string);

     }

     

    
    
}

?>