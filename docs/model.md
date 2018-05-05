# Model CRUD

This command is used to generate a CRUD model in a module

   ``` bash
   hotrod create:model <namespace> <name> <table-name> <id-field>
   ```
   
Where `<namespace>` is a M2 custom module name `Vendor_Module` and `name` is a name of a Model, `table-name` is a table name 
for which this resource model is creating for and `id-field` is a identification field for the table name.

  ```bash
  hotrod create:model Testing_Test Test test test_id
  ```
  
It will not only generate the `Testing\Test\Model\Test` Class in `app/code/Testing/Test/Model/Test.php` but also
all You need to use it
* `app/code/Testing/Test/Model/ResourceModel/Test.php` Resource Model file
* `app/code/Testing/Test/Model/ResourceModel/Test/Collection.php` Collection file
* `app/code/Testing/Test/Api/TestRepositoryInterface.php` Repository Interface file
* `app/code/Testing/Test/Model/TestRepository.php` Repository file
* `app/code/Testing/Test/etc/di.xml` DI file with a preference for repository file

> Before generating the Model, HotRod Cli checks if the module exists, if not, it creates one for You.
