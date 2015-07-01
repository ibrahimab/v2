<?php
namespace AppBundle\Doctrine\DQL\Functions;
use       Doctrine\ORM\Query\Lexer;
use       Doctrine\ORM\Query\Parser;
use       Doctrine\ORM\Query\SqlWalker;
use       Doctrine\ORM\Query\AST\Functions\FunctionNode;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class Rand extends FunctionNode
{
    /**
     * @param  Parser $parser
     * @return void
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @param  SqlWalker $sqlWalker
     * @return string
     */
    public function getSQL(SqlWalker $sqlWalker)
    {
        return 'RAND()';
    }
}