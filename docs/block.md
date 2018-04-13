# Block

In order to generate a Block class use the next command

   ``` bash
   hotrod create:block <namespace> <blockname>
   ```
   
Where `<namespace>` is a M2 custom module name `Vendor_Module` and `blockname` is a name of a Block Class.
Running this command

   ``` bash
   hotrod create:block Vendor_Module TestBlock
   ```
will generate a `Vendor\Module\Block\TestBlock` class in `app/code/Vendor/Module/Block/TestBlock.php` file.

## Options

It is also possible to generate the block for the Admin part, setting the `-admin` or `--adm` option.
In such case the example command will be

   ``` bash
   hotrod create:block Vendor_Module TestBlock --admin=true
   ```
   
It will generate a `Vendor\Module\Block\Adminhtml\TestBlock` class in `app/code/Vendor/Module/Block/Adminhtml/TestBlock.php` file.

> Before generating the block HotRod Cli checks if the module exists, if not, it creates one for You.