# Observer

In order to generate an Observer class use the next command

   ``` bash
   hotrod create:observer <namespace> <event> <observer>
   ```
   
Where `<namespace>` is a M2 custom module name `Vendor_Module`, `<event>` is a name of an Event and `<observer>` is a name 
of Observer.
Running this command

   ``` bash
   hotrod create:observer Testing_Test simple_test_event EventListener
   ```
   
will generate a `Testing\Test\Observer\EventListener` class in `app/code/Testing/Test/Observer/EventListener.php` file and will add it to the created
`app/code/Testing/Test/etc/events.xml` file

> Before generating the Observer, HotRod Cli checks if the module exists, if not, it creates one for You.
