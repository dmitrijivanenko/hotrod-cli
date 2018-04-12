# Module

New module structure generation is an basic feature for the HotRod Cli. Just run the command with 
a given namespace `Company_Module` 

   ``` bash
   hotrod module:create Test_Module
   ```
   
This command will generate for You a new directory `app/code/Test/Module` with two files:
* `app/code/Test/Module/etc/module.xml`
* `app/code/Test/Module/registration.php`

Following the M2 convection, in both the name of the module will be `Test_Module`.