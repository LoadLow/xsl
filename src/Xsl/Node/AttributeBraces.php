<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMAttr;
use DOMDocument;
use Genkgo\Xsl\Util\FetchNamespacesFromDocument;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\AttributeTransformerInterface;

/**
 * Class AttributeMatch
 * @package Genkgo\Xsl\Xsl\Element
 */
class AttributeBraces implements AttributeTransformerInterface
{
    /**
     * @var Compiler
     */
    private $xpathCompiler;

    /**
     * @param Compiler $compiler
     */
    public function __construct(Compiler $compiler)
    {
        $this->xpathCompiler = $compiler;
    }

    /**
     * @param DOMDocument $document
     * @return bool
     */
    public function supports(DOMDocument $document)
    {
        return true;
    }

    /**
     * @param DOMAttr $attribute
     */
    public function transform(DOMAttr $attribute)
    {
        $attribute->nodeValue = '{' . $this->xpathCompiler->compile(
            substr($attribute->nodeValue, 1, -1),
            FetchNamespacesFromDocument::fetch($attribute->ownerDocument)
        ) . '}';

    }
}