# Collection

To make a Collection Class use the command 

   ```bash
   create:collection <namespace> <name> <table-name> <id-field>
   ```

Where `<namespace>` is a M2 custom module name `Vendor_Module` and `name` is a name of a Collection's Model, `table-name` is a table name 
for which this collection is creating for and `id-field` is a identification field for the table name.

Running this command

   ``` bash
   hotrod create:collection Testing_Test Test test test_id
   ```
Will generate a `Testing\Test\Model\ResourceModel\Test\Collection` class in a `app/code/Testing/Test/Model/ResourceModel/Test/Collection.php` file.

> Before generating the Collection, HotRod Cli checks if the module exists, if not, it creates one for You.