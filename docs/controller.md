# Controller

This command is used to generate a controller in a module

 ```bash
 hotrod create:controller <namespace> <route>
 ```
 
 Where `<namespace>` is a M2 custom module name `Vendor_Module`, `<route>` is a route path. For a example
 
 ```bash
  hotrod create:controller Testings_Test testings/testing/test
  ```
  
It will not only generate the `Testings\Test\Controller\Testing\Test` Class in `app/code/Testings/Test/Controller/Testing/Test.php` but also
all You need to set up that controller
* `app/code/Testings/Test/Block/Testing.php` Block file
* `app/code/Testings/Test/etc/frontend/routes.xml` Routes file
* `app/code/Testings/Test/view/frontend/layout/testings_testing_test.xml` Layout file
* `app/code/Testings/Test/view/frontend/templates/test.phtml` Template file

## Options

### Admin

It is also possible to generate Controller with all needed files in admin scope, by setting the `--admin` option.

   ``` bash
   hotrod create:controller Testings_Test testings/testing/test --admin=true
   ```
   
In such case generated files will be
* `app/code/Testings/Test/Block/Adminhtml/Testing.php`
* `app/code/Testings/Test/Controller/Adminhtml/Testing/Test.php`
* `app/code/Testings/Test/etc/adminhtml/routes.xml`
* `app/code/Testings/Test/view/adminhtml/layout/testings_testing_test.xml`
* `app/code/Testings/Test/view/adminhtml/templates/test.phtml`

### Restrictions

If for some reason you don't need some of those files to be generated you can use restrictions options

#### No Block
 `--no-block`
   ``` bash
   hotrod create:controller Testings_Test testings/testing/test --no-block=true
   ```
   
#### No Route
 `--no-route`
   ``` bash
   hotrod create:controller Testings_Test testings/testing/test --no-route=true
   ```   
#### No Layout
 `--no-layout`
   ``` bash
   hotrod create:controller Testings_Test testings/testing/test --no-layout=true
   ```
#### No Template
 `--no-template` 
   ``` bash
   hotrod create:controller Testings_Test testings/testing/test --no-template=true
   ``
   
> Before generating the controller HotRod Cli checks if the module exists, if not, it creates one for You.
