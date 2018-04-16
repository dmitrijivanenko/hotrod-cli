# Layout

For the Layout file generation You can use

   ``` bash
   hotrod create:layout <namespace> <layout-name> <layout-file-name> <block-class> <template>
   ```
   
Where `<namespace>` is a M2 custom module name `Vendor_Module`, `<layout-name>` is a name of a layout, 
`<layout-file-name>` is a layout filename, `<block-class>` is a Block's php class and `<template>` is a path for the template file.
It is little bit complected, but there is nothing too hard here.

   ``` bash
   hotrod create:layout Testings_Test Test test-layout Testings\\Test\\Block\\Test test
   ```
   
It will generate the file a `app/code/Testings/Test/view/frontend/layout/test-layout.xml` which will contain

   ``` xml
   <?xml version="1.0"?>
   <page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" layout="1column" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
       <referenceContainer name="content">
           <block class="Testings\Test\Block\Test" name="test" template="Testings_Test::test.phtml" />
       </referenceContainer>
   </page>
   ```

## Options

It is also possible to generate the layout for the Admin part, setting the `-admin` option.
In such case the example command will be

   ``` bash
   hotrod create:layout Testings_Test Test test-layout Testings\\Test\\Block\\Test test --admin=true
   ```
   
It will generate a `app/code/Testings/Test/view/adminhtml/layout/test-layout.xml` file.

> Before generating the layout HotRod Cli checks if the module exists, if not, it creates one for You.