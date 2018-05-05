# Repository

This command is used to generate a repository in a module for the given CRUD model

    ```
    hotrod create:repository <namespace> <interface-name> <model-class>
    ```
    
Where `<namespace>` is a M2 custom module name `Vendor_Module`, `<interface-name>` is a name of Repository Interface and
`<model-class>` Class of the CRUD model

  ```bash
  hotrod create:repository Testing_Test TestInterface Testing\\Test\\Model\\Test
  ```

It will generate:
* `app/code/Testing/Test/Api/TestInterface.php` Repository Interface
* `app/code/Testing/Test/Model/TestRepository.php` Repository Class with stubs (Yuo have to write in your own code here)
* `app/code/Testing/Test/etc/di.xml` DI file with added preference for the repository

> Before generating the Repository, HotRod Cli checks if the module exists, if not, it creates one for You.
     