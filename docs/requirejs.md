# RequireJS Config file

In order to generate RequireJS configuration file you can use

   ``` bash
   hotrod create:requirejs-config <namespace>
   ```
   
Where `<namespace>` is a M2 custom module name `Vendor_Module`.

   ``` bash
   hotrod create:requirejs-config Test_Testings
   ```
   
It will generate the file a `Tapp/code/est/Testings/view/frontend/requirejs-config.js` which will contain

   ``` javascript
   var config = {
       map: {
           "*": {
   
           }
       }
   };
   ```

## Options

It is also possible to generate the configuration file for the Admin section, by setting the `-admin` option.
In such case the example command will be

   ``` bash
   hotrod create:requirejs-config Test_Testings --admin=true
   ```
   
It will generate a `app/code/Test/Testings/view/adminhtml/requirejs-config.js` file.

> Before generating the file HotRod Cli checks if the module exists, if not, it creates one for You.