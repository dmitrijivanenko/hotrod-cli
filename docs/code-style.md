# Code Style Fix

The HotRod Cli comes with build in Code Style fixes based on [friendsofphp/php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer). 

   ```bash
   hotrod psr:fix
   ```
   
This command will check the `/app/code` directory and will try to fix all PSR2 issues. If you want to fix the code in specific directory
add the `--dir` option 

   ```bash
   hotrod psr:fix --dir=app/code/test
   ```