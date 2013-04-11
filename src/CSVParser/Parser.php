<?php namespace CSVParser;

use SimpleXMLElement;
use Exception;

/**
* Parser
*
* @category CSVParser
* @package  CSVParser
* @author   Aran Wilkinson <aran3001@gmail.com>
* @license  
* @link     https://github.com/aranw/csvparser
*/
class Parser implements ParserInterface
{
    /**
     * Resource being parsed
     *
     * @var mixed
     *
     * @access public
     */
    public $resource;

    /**
     * Final output after parsing
     *
     * @var array
     *
     * @access public
     */
    public $output = array();

    /**
     * Stores SimpleXMLElement for XML Output
     *
     * @var SimpleXMLElement
     *
     * @access public
     */
    public $xml;

    /**
     * Stores the original filename parsed into the constructor
     *
     * @var mixed
     *
     * @access public
     */
    public $filename;

    /**
     * The initial constructor for the class.
     * 
     * @param mixed $filename Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        // Check file exists
        if ( ! is_null($this->filename)) {
            // Load file contents
            if ($this->isFileResource($this->filename)) {
                $this->loadFromResource($this->filename);
            } else {
                $this->loadFromPath($this->filename);
            }
        } else {
            throw new Exception("No file provided.");
        }
    }

    /**
     * parseResource takes $resource and parses the CSV file into an array
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function parseResource()
    {
        $row = 1;
        $processedData = array();
        $headings = array();

        while(($data = fgetcsv($this->resource, 1000, ",")) !== FALSE) {
            $num = count($data); // Count number of fields in $row
            $record = array();
            
            // Loop through the fields in $row
            for ($i = 0; $i < $num; $i++) {
                // If were on line 1 we must have the headings
                if ($row == 1) {
                    $headings[$i] = $data[$i];
                } else {
                    $record[$fields[$i]] = $data[$i];
                }
            }

            if ($row != 1) {
                // address is set manually here for XML output
                // Ideally this should be provided via an input or some other method
                $processedData[]['address'] = $record;
            }

            $row++;
        }

        // Save the processed data for use later
        $this->output = $processedData;

        fclose($this->resource);
    }

    /**
     * getJson takes the parsed CSV Array and outputs it encoded as JSON
     * 
     * @access public
     *
     * @return mixed 
     */
    public function getJson()
    {
        return json_encode($this->output);
    }

    /**
     * getXml takes the parsed CSV Array and processes it into an XML result and returns it
     * 
     * @access public
     *
     * @return mixed
     */
    public function getXml()
    {
        $this->xml = new SimpleXMLElement("<?xml version=\"1.0\"?><addresses></addresses>");
        $this->arrayToXml($this->output, $this->xml);

        return $this->xml->asXml();
    }

    /**
     * getOutput returns the parsed CSV Array
     * 
     * @access public
     *
     * @return Array
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * getFilename returns the original filename
     * 
     * @access public
     *
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * arrayToXml takes the output $array and the xmlArray as a reference 
     * and loops over $array adding xml childs to the referenced xmlArray
     * 
     * @param mixed $array     Original Array output or multidimensional arrays.
     * @param mixed \&xmlArray Reference to the XML Array used for output
     *
     * @access private
     *
     * @return void.
     */
    private function arrayToXml($array, &$xmlArray)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if ( ! is_numeric($key)) {
                    $subnode = $this->xml->addChild("$key");
                    $this->arrayToXml($value, $subnode);
                } else {
                    $this->arrayToXml($value, $xmlArray);
                }
            } else {
                $xmlArray->addChild("$key", "$value");
            }
        }
    }

    /**
     * loadFromResource
     * 
     * @param mixed $input Description.
     *
     * @access private
     *
     * @return void.
     */
    private function loadFromResource($input) 
    {
        if (get_resource_type($input) == 'stream') {
            $this->resource = $input;
        } else {
            throw new Exception("Wrong resource type");
        }
    }

    /**
     * loadFromPath is used to open the file if a resource isn't passed to the class
     * 
     * @param mixed $input Description.
     *
     * @access private
     *
     * @return void
     */
    private function loadFromPath($input)
    {
        if (($this->resource = fopen($filename, "r")) == FALSE) {
            throw new Exception("Unable load file.");
        }
    }

    /**
     * isFileResource checks to see if the passed file is a resource or not
     * 
     * @param mixed $input Description.
     *
     * @access private
     *
     * @return boolean
     */
    private function isFileResource($input)
    {
        return (is_resource($input) && get_resource_type($input) == 'stream');
    }

}