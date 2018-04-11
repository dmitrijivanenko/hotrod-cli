# Quick start

It is recommended to use the package only in developer environment

## Installation
   
   You can install the package via composer:
   
   ``` bash
   composer require --dev dmitrijivanenko/hotrod-cli
   ```
   
## Usage

To call the cli you have to be in root directory of your project, then just enter

   ``` bash
   ./vendor/bin/hotrod
   ```
   
It will list all possible commands in HotRod Cli. Type 
   ``` bash
   ./vendor/bin/hotrod <command> --help
   ```
to show the help, where `<command>` is any possible command. It is pretty useful to save an alias for this

   ``` bash
   alias hotrod='./vendor/bin/hotrod'
   ```
   
 To write in the alias permanently, don't forget to save it in `bash_profile`.