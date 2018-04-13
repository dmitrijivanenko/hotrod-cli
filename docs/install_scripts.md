# Install Scripts

HotRod Cli can easily generate an install/upgrade scripts for You.

## Install Schema

To generate the Install Schema script type

   ``` bash
   hotrod create:install-schema <namespace>
   ```
Where `<namespace>` is a M2 custom module name `Vendor_Module`. It will generate a `Vendor\Module\Setup\InstallSchema` class in `Vendor/Module/Setup/InstallSchema.php` file.

## Install Data

To generate the Install Data script type

   ``` bash
   hotrod create:install-data <namespace>
   ```
Where `<namespace>` is a M2 custom module name `Vendor_Module`. It will generate a `Vendor\Module\Setup\InstallData` class in `app/code/Vendor/Module/Setup/InstallData.php` file.

## Upgrade Schema

To generate the Upgrade Schema script type

   ``` bash
   hotrod create:upgrade-schema <namespace>
   ```
Where `<namespace>` is a M2 custom module name `Vendor_Module`. It will generate a `Vendor\Module\Setup\UpgradeShema` class in `app/code/Vendor/Module/Setup/UpgradeShema.php` file.

## Upgrade Data

To generate the Upgrade Data script type

   ``` bash
   hotrod create:upgrade-data <namespace>
   ```
Where `<namespace>` is a M2 custom module name `Vendor_Module`. It will generate a `Vendor\Module\Setup\UpgradeData` class in `app/code/Vendor/Module/Setup/UpgradeData.php` file.



> All listed commands before generating the Install/Upgrade script checks if the module exists, if not, it creates one for You.