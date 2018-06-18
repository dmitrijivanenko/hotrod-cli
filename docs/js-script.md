# JS file

Hotrod Cli can generate the JavaScript files for you and set them up in template, if it is necessary.

   ``` bash
   hotrod create:js-script <namespace> <script-name>
   ```
   
Where `<namespace>` is a M2 custom module name `Vendor_Module` and `<script-name>` the script name. 

By running 

   ``` bash
   hotrod create:js-script Testing_Test test
   ```
   
You will receive `app/code/Testing/Test/view/frontend/web/js/test.js` file which is also will be added to requirejs config file.

## Options

### Admin

It is also possible to generate the JavaScript file for the Admin section, by setting the `--admin` option.
In such case the example command will be

   ``` bash
   hotrod create:js-script Testing_Test test --admin=true
   ```
   
It will generate a `app/code/Testing/Test/view/adminhtml/web/js/test.js` file.

### Template

The command can add the `mage-init` code simply by adding the template option. Lets generate the template first.

   ``` bash
   hotrod create:template Testing_Test another-test
   ```
   
and run the js generation

   ``` bash
   hotrod create:js-script Testing_Test another-test  --template=Testing//Test//view//frontend//templates//another-test.phtml
   ```
   
It will add the `mage-init` block to the template

   ``` html
   <span>Hello World</span>
   
   <script type="text/x-magento-init">
   {
       "*": {
           "another-test": {
               "text": "HELLO WORLD"
           }
       }
   }
   </script>
   ```
   
### Script type

You can generate simple script or widget by running this command, by default it will generate the simple js script like this

   ``` javascript
   define([
     "jquery",
     "mage/translate",
   ], function ($, $t) {
     'use strict';
   
     return function (config) {
       console.log(config.text);
     }
   });
   ```

but you can also generate the widget script by setting the `--type` option

   ``` bash
   hotrod create:js-script Testing_Test another-one-test --type=widget 
   ```
   
it will generate the `app/code/Testing/Test/view/frontend/web/js/another-one-test.js` file with simple widget skeleton

   ``` javascript
   define([
     "jquery",
     "jquery/ui"
   ], function ($) {
     'use strict';
   
     $.widget('mage.another-one-test', {
       options: {
         text: "hello world"
       },
   
       _create: function() {
         console.log(this.options.text);
       }
   
     });
   });
   ```

> Before generating the js file HotRod Cli checks if the module exists, if not, it creates one for You.