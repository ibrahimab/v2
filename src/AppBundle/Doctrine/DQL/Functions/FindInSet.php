<?php
namespace AppBundle\Doctrine\DQL\Functions;
use       Doctrine\ORM\Query\Lexer;
use       Doctrine\ORM\Query\Parser;
use       Doctrine\ORM\Query\SqlWalker;
use       Doctrine\ORM\Query\AST\Functions\FunctionNode;
use       Doctrine\ORM\Query\AST\InputParameter;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class FindInSet extends FunctionNode
{
    /**
     * @var InputParameter
     */
    private $needleExpression;
    
    /**
     * @var InputParameter
     */
    private $haystackExpression;
    
    /**
     * @param  Parser $parser
     * @return void
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        
        $this->needleExpression = $parser->arithmeticPrimary();
        
        $parser->match(Lexer::T_COMMA);
        
        $this->haystackExpression = $parser->arithmeticPrimary();
        
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @param  SqlWalker $walker
     * @return string
     */
    public function getSQL(SqlWalker $walker)
    {
        return 'FIND_IN_SET(' . $this->needleExpression->dispatch($walker) . ', ' . $this->haystackExpression->dispatch($walker) . ')';
    }
}