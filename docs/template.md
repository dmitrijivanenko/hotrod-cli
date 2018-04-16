# Template

For the template file generation You can use

   ``` bash
   hotrod create:template create:template <namespace> <template-name>
   ```
   
Where `<namespace>` is a M2 custom module name `Vendor_Module`, `<template-name>` is a name of a template.

   ``` bash
   hotrod create:template Testing_Test test
   ```
   
It will generate the a `app/code/Testing/Test/view/frontend/templates/test.phtml` file

## Options

It is also possible to generate the template for the Admin part, setting the `-admin` option.
In such case the example command will be

   ``` bash
   hotrod create:template Testing_Test test --admin=true
   ```
   
It will generate a `app/code/Testing/Test/view/adminhtml/templates/test.phtml` file.

> Before generating the template HotRod Cli checks if the module exists, if not, it creates one for You.