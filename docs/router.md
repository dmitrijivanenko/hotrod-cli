# Router

For the route file generation You can use

   ``` bash
   hotrod create:route <namespace> <route-name>
   ```
   
Where `<namespace>` is a M2 custom module name `Vendor_Module`, `<route-name>` is a name of the route or simply first route's path segment.

   ``` bash
   hotrod create:route Testing_Test route
   ```
   
It will generate the file a `app/code/Testing/Test/tec/frontend/routes.xml` which will contain

   ``` xml
   <?xml version="1.0" ?>
   <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:App/etc/routes.xsd">
       <router id="standard">
           <route frontName="route" id="route">
               <module name="Testing_Test"/>
           </route>
       </router>
   </config>
   ```
   
## Multiple routes

As in Magento 2 it can be several routes pointing to the same module HotRod Cli can add multiple routes.
If the file already generated, and the given `<route-name>` is not in use, HotRod Cli will add the new route into that file

   ``` bash
   hotrod create:route Testing_Test route
   ```
   
It will add to the existing file `app/code/Testing/Test/tec/frontend/routes.xml` the new `route2` route

   ``` xml
   <?xml version="1.0" ?>
   <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:App/etc/routes.xsd">
       <router id="standard">
           <route frontName="route" id="route">
               <module name="Testing_Test"/>
           </route>
           <route frontName="route2" id="route2">
               <module name="Testing_Test"/>
           </route>
       </router>
   </config>
   ```

## Options

It is also possible to generate the `routes.xml` file for the Admin part, setting the `-admin` or `--adm` option.

   ``` bash
   hotrod create:route Testings_Test route --admin=true
   ```
   
It will generate a `app/code/Testings/Test/etc/adminhtml/routes.xml` file.

> Before generating the layout HotRod Cli checks if the module exists, if not, it creates one for You.