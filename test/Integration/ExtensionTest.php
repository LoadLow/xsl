<?php
namespace Genkgo\Xsl\Integration;

use DOMDocument;
use Genkgo\Xsl\Config;
use Genkgo\Xsl\Stubs\Extension\MyExtension;
use Genkgo\Xsl\XsltProcessor;

class ExtensionTest extends AbstractIntegrationTestCase
{
    public function testXpathFunction()
    {
        $extension = new MyExtension();

        $config = new Config();
        $config->setExtensions([$extension]);

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/my-extension.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $processor = new XsltProcessor($config);
        $processor->importStylesheet($xslDoc);
        $processorResult = $processor->transformToXML($xmlDoc);

        $this->assertEquals('Hello World was called and received 20 arguments!', trim($processorResult));
    }

    public function testXpathFunctionXsl1()
    {
        $extension = new MyExtension();

        $config = new Config();
        $config->setExtensions([$extension]);

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/my-extension.xsl');
        $xslDoc->documentElement->setAttribute('version', '1.0');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $processor = new XsltProcessor($config);
        $processor->importStylesheet($xslDoc);
        $processorResult = $processor->transformToXML($xmlDoc);

        $this->assertEquals('Hello World was called and received 20 arguments!', trim($processorResult));
    }

    public function testClosure()
    {
        $extension = new MyExtension();

        $config = new Config();
        $config->addExtension($extension);

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/closure.xsl');
        $xslDoc->documentElement->setAttribute('version', '1.0');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $processor = new XsltProcessor($config);
        $processor->importStylesheet($xslDoc);
        $processorResult = $processor->transformToXML($xmlDoc);

        $this->assertEquals('20', trim($processorResult));
    }

    public function testMethod()
    {
        $extension = new MyExtension();

        $config = new Config();
        $config->addExtension($extension);

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/method.xsl');
        $xslDoc->documentElement->setAttribute('version', '1.0');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $processor = new XsltProcessor($config);
        $processor->importStylesheet($xslDoc);
        $processorResult = $processor->transformToXML($xmlDoc);

        $this->assertEquals('24', trim($processorResult));
    }
}
