<?php 
require('vendor/autoload.php');

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use CSVParser\ParseCommand;

class ParseCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testJson()
    {
        $application = new Application();
        $application->add(new ParseCommand());

        $command = $application->find('parse');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array('command' => $command->getName(), 'filename' => 'tests/test.csv')
        );

        $this->assertRegExp('/[JSON Result]/', $commandTester->getDisplay());
    }

    public function testXml()
    {
        $application = new Application();
        $application->add(new ParseCommand());

        $command = $application->find('parse');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array('command' => $command->getName(), 'filename' => 'tests/test.csv', '--xml' => true)
        );

        $this->assertRegExp('/[XML Result]/', $commandTester->getDisplay());      
    }
}