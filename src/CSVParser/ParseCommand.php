<?php namespace CSVParser;

use CSVParser\Parser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
* ParseCommand
*
* @uses     Command
*
* @category CSVParser
* @package  CSVParser
* @author   Aran Wilkinson <aran3001@gmail.com>
* @license  
* @link     https://github.com/aranw/csvparser
*/
class ParseCommand extends Command 
{
    /**
     * configure the command
     * 
     * @access protected
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('parse')
            ->setDescription('Parse CSV File to ouput JSON or XML')
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'CSV File Required'
            )
            ->addOption(
                'xml',
                null,
                InputOption::VALUE_NONE,
                'Output result as XML'
            )
            ->addOption(
                'resultName',
                null,
                InputOption::VALUE_OPTIONAL,
                'Set Result Name for Output'
            )
            ->addOption(
                'output-file',
                null,
                InputOption::VALUE_OPTIONAL,
                'Output result to file'
            );
    }

    /**
     * execute
     * 
     * @param mixed \InputInterface
     * @param mixed \OutputInterface
     *
     * @access protected
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = array();
        $filename = $input->getArgument('filename');
        $resultName = $input->getArgument('resultName');

        $output->writeln("<info>[Parsing]</info> $filename");

        if ($resultName) {
            $options['resultName'] = $resultName;
        }

        try {
            $parser = new Parser($filename, $options);
        } catch (Exception $e) {
            $output->writeln("<error>[Warning]</error> Unable to parse $filename");
            exit;
        }

        $parser->parseResource();

        $xmlOutput = $input->getOption('xml');

        $result = ($xmlOutput) ? $parser->getXml() : $parser->getJson();

        if ($input->getOption('output-file')) {
            $outputFilename = $input->getOption('output-file');
            // Create a new file from output-file 
            $outputFile = fopen($outputFilename, "w");
            fwrite($outputFile, $result);
            // Save $result to file
            fclose($outputFile);
        } else {
            // Else output file
            if ($xmlOutput) {
                $output->writeln("<info>[XML Result]</info>");
            } else {
                $output->writeln("<info>[JSON Result]</info>");
            }
            $output->writeln($result);
        }
    }
}