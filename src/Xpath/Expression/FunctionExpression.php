<?php
namespace Genkgo\Xsl\Xpath\Expression;

use DOMNode;
use Genkgo\Xsl\Callback\ReplaceFunctionInterface;
use Genkgo\Xsl\Util\FetchNamespacesFromNode;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xpath\ExpressionInterface;
use Genkgo\Xsl\Xpath\Lexer;

class FunctionExpression implements ExpressionInterface
{
    /**
     * @var FunctionMap
     */
    private $functions;

    public function __construct(FunctionMap $functions)
    {
        $this->functions = $functions;
    }

    /**
     * @param Lexer $lexer
     * @return bool
     */
    public function supports(Lexer $lexer)
    {
        $nextToken = $lexer->peek($lexer->key() + 1);
        return $nextToken === '(';
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @param array $tokens
     * @return array
     */
    public function merge(Lexer $lexer, DOMNode $currentElement, array $tokens)
    {
        return array_merge($tokens, $this->createFunctionTokens($lexer, $currentElement));
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return string[]
     */
    private function createFunctionTokens(Lexer $lexer, DOMNode $currentElement)
    {
        $token = $lexer->current();
        $documentElement = $currentElement->ownerDocument->documentElement;
        $namespaces = FetchNamespacesFromNode::fetch($documentElement);
        $functionName = $this->convertTokenToFunctionName($token, $namespaces);

        if ($this->functions->has($functionName)) {
            $function = $this->functions->get($functionName);
            if ($function instanceof ReplaceFunctionInterface) {
                return $function->replace($lexer, $currentElement);
            }
        }

        return [$token];
    }

    /**
     * @param $token
     * @param array $namespaces
     * @return string
     */
    private function convertTokenToFunctionName($token, array $namespaces)
    {
        $functionName = strpos($token, ':');

        if ($functionName !== false) {
            $prefix = substr($token, 0, $functionName);
            if (isset($namespaces[$prefix])) {
                $token = $namespaces[$prefix] . ':' . substr($token, $functionName + 1);
            }
        }

        return $token;
    }
}
