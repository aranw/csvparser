<?php namespace CSVParser;

/**
* ParserInterface
*
* @category CSVParser
* @package  CSVParser
* @author   Aran Wilkinson <aran3001@gmail.com>
* @license  
* @link       
*/
interface ParserInterface
{
    /**
     * parseResource should parse CSV file
     * 
     * @access public
     *
     * @return null
     */
    public function parseResource();

    /**
     * getXml should return the XML result
     * 
     * @access public
     *
     * @return null
     */
    public function getXml();

    /**
     * getJson should return a JSON result
     * 
     * @access public
     *
     * @return null
     */
    public function getJson();

    /**
     * getOutput should return an array
     * 
     * @access public
     *
     * @return null
     */
    public function getOutput();

    /**
     * getFilename should return the filename
     * 
     * @access public
     *
     * @return null
     */
    public function getFilename();
} 