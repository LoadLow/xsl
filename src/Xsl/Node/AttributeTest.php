<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMDocument;
use DOMElement;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\FetchNamespacesFromDocument;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;

/**
 * Class AttributeSelect
 * @package Genkgo\Xsl\Xsl\Element
 */
class AttributeTest implements ElementTransformerInterface
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
     * @param DOMElement $element
     */
    public function transform(DOMElement $element)
    {
        if ($element->hasAttribute('test')) {
            $element->setAttribute(
                'test',
                $this->xpathCompiler->compile(
                    $element->getAttribute('test'),
                    FetchNamespacesFromDocument::fetch($element->ownerDocument)
                )
            );
        }
    }
}