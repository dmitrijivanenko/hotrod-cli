# Resource Model

In order to create a Resource Model class run this command

   ``` bash
   hotrod create:resource-model <namespace> <name> <table-name> <id-field>
   ```
Where `<namespace>` is a M2 custom module name `Vendor_Module` and `name` is a name of a Resource Model, `table-name` is a table name 
for which this resource model is creating for and `id-field` is a identification field for the table name.

Running this command

   ``` bash
   hotrod create:resource-model Testing_Test Test test test_id
   ```
Will generate a `Testings\Test\Model\ResourceModel\Test` class in a `app/code/Testings/Test/Model/ResourceModel/Test.php` file.

> Before generating the Resource Model, HotRod Cli checks if the module exists, if not, it creates one for You.