<?php
namespace AppBundle\Doctrine\DQL\Functions;
use       Doctrine\ORM\Query\Lexer;
use       Doctrine\ORM\Query\SqlWalker;
use       Doctrine\ORM\Query\Parser;
use       Doctrine\ORM\Query\AST\Functions\FunctionNode;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class UnixTimestamp extends FunctionNode
{
    /**
     * @var string
     */
    protected $expression;

    /**
     * @param  SqlWalker $walker
     * @return string
     */
    public function getSql(SqlWalker $walker)
    {
        return sprintf('UNIX_TIMESTAMP(%s)', $walker->walkArithmeticPrimary($this->expression));
    }

    /**
     * @param  Parser $parser
     * @return void
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->expression = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}