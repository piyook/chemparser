<?php

use App\ChemSymb\ChemParser;

use function PHPUnit\Framework\assertTrue;

class ChemSymbTest extends \PHPUnit\Framework\TestCase
{


    /** @test */
    public function check_parser_returns_an_html_string(){

        $parser = new ChemParser;
        $input = 123;
        $return_string = $parser->generate($input);

        $this->assertFalse($return_string == strip_tags($return_string));

    }

    
    
}

?>