<?php 
require('vendor/autoload.php');

use CSVParser\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public $options = array(
            'resultName' => 'address'
        );
        
    public function testConstructor()
    {
        $application = new Parser('tests/test.csv', $this->options);
        $this->assertEquals('tests/test.csv', $application->getFilename());
    }

    public function testParseResource()
    {
        $resource = null;

        // Open file in PHP
        if (($resource = fopen("tests/test.csv", "r")) == FALSE) {
            throw new Exception("Unable load file.");
        }
        // Pass file resource to Parser
        $application = new Parser($resource, $this->options);
        $this->assertEmpty($application->getOutput());
        $application->parseResource();
        $this->assertCount(1, $application->getOutput());
    }

    public function testParseResourceFunction()
    {
        $application = new Parser('tests/test.csv', $this->options);
        $this->assertEmpty($application->getOutput());
        $application->parseResource();
        $this->assertCount(1, $application->getOutput());
    }

    public function testOutput()
    {
        $application = new Parser('tests/test.csv', $this->options);
        $application->parseResource();

        $result = $application->getOutput();

        $this->assertEquals('Bob', $result[0]['address']['name']);
    }

    public function testJson()
    {
        $application = new Parser('tests/test.csv', $this->options);
        $application->parseResource();

        $result = $application->getJson();

        $this->assertJsonStringEqualsJsonString('[{"address":{"name":"Bob","address_1":"1 A Street","address_2":"A town","address_postcode":"AA11AA"}}]', $result);
    }

    public function testXml()
    {
        $application = new Parser('tests/test.csv', $this->options);
        $application->parseResource();

        $result = $application->getXml();

        $this->assertXmlStringEqualsXmlString('<?xml version="1.0"?>
<addresses><address><name>Bob</name><address_1>1 A Street</address_1><address_2>A town</address_2><address_postcode>AA11AA</address_postcode></address></addresses>
', $result);
    }

}