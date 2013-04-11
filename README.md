# PHP CSV Parser Class with CLI Tool

**Note this is not production ready code it was done in one evening as an excerise and it is not fully complete for production use.**

The CSV Parser class has been designed to be used either via the CLI or by including the PHP class. The package includes a ```composer.json``` for the necessary files to use the CLI Tool, this part of the package uses the Symfony2 Console Component to provide the basic interactions with the CLI.

## How to use?

To use the PHP CLI Tool, you'll first need to ensure all the dependencies are in place by running ```composer install```. Once you have the Symfony2 package installed you'll need to make sure the console command is executable. If it is not you'll need to execute the following command ```chmod +x app/console```. Then the file will be ready for use.

### Basic Usage

The command has two optional parameters and one required parameter. To parse a file and return the output as JSON use ```app/console parse test.csv``` this'll output the result via STDOUT. 

The following options are currently available. 

- --xml
- --output-file=

```--xml``` takes no value whereas ```--output-file``` takes a filename, and using this option will output the result to the given file. 
 