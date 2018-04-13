# Helper

In order to create a Helper class run this command

   ``` bash
   hotrod create:helper <namespace> <name>
   ```
Where `<namespace>` is a M2 custom module name `Vendor_Module` and `name` is a name of a Helper Class.
Running this command

   ``` bash
   hotrod create:helper Testings_Test CustomHelper
   ```
Will generate a `Testings\Test\Helper\CustomHelper` class in a `app/code/Testings/Test/Helper/CustomHelper.php` file.

> Before generating the helper HotRod Cli checks if the module exists, if not, it creates one for You.