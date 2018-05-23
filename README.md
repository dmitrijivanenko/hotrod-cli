<p align="center"><img width="400" src="https://dmitrijivanenko.github.io/hotrod-cli/img/hotrodcli.jpg"></p>

<p align="center">
<a href="https://scrutinizer-ci.com/g/dmitrijivanenko/hotrod-cli/?branch=master"><img src="https://scrutinizer-ci.com/g/dmitrijivanenko/hotrod-cli/badges/quality-score.png?b=master" alt="Scrutinizer Code Quality"></a>
<a href="https://travis-ci.org/dmitrijivanenko/hotrod-cli"><img src="https://travis-ci.org/dmitrijivanenko/hotrod-cli.svg?branch=master" alt="Build Status"></a>
<a href="https://codecov.io/gh/dmitrijivanenko/hotrod-cli"><img src="https://codecov.io/gh/dmitrijivanenko/hotrod-cli/branch/master/graph/badge.svg" alt="codecov"></a>
<a href="https://gitter.im/hotrod-cli/Lobby?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge"><img src="https://badges.gitter.im/hotrod-cli/Lobby.svg" alt="Join the chat at https://gitter.im/hotrod-cli/Lobby"></a>
</p>

This package helps you generate Magento 2 code using a clean commandline tool. All classes, templates, layouts and etc. can be dynamically generated and put together. 

## Features

- Generates different kind of magento 2 module components
- has a build in UI
- Easy to start using

<p align="center"><img width="800" src="https://dmitrijivanenko.github.io/hotrod-cli/img/screens.png"></p>

## Documentation

   You'll find full documentation [here](https://dmitrijivanenko.github.io/hotrod-cli/#/).

## Example
   
   Lets review an example
   
   ``` bash
   vendor/bin/hotrod create:controller Dmiva_Test dmiva/testing/test
   ```
   
   This line tries to create a controller for the route `dmiva/testing/test` in `Dmiva_Test` namespace. Magically it
   creates for You a module `Dmiva_Test` if it is not exists and generates files :
   
   - `app/code/Dmiva/Test/registration.php`   
   - `app/code/Dmiva/Test/etc/module.xml`   
   - `app/code/Dmiva/Test/etc/frontend/routes.xml`   
   - `app/code/Dmiva/Test/view/frontend/layout/dmiva_testing_test.xml`   
   - `app/code/Dmiva/Test/Controller/Testing/Test.php`   
   - `app/code/Dmiva/Test/Block/Testing.php`
   - `app/code/Dmiva/Test/view/frontend/templates/test.phtml`
   
   To open the User Interface, run this command
   
   ``` bash
   vendor/bin/hotrod-ui
   ```
   
   It will build the Angular app and run the basic build in php server on `http://localhost:8008/`. 

## Installation
   
   You can install the package via composer:
   
   ``` bash
   composer require --dev dmitrijivanenko/hotrod-cli
   ```   
   
## Contributing

Help is very welcomed. We accept contributions via Pull Requests on [Github](https://github.com/dmitrijivanenko/hotrod-cli).
Please read and understand the contribution [guide](https://dmitrijivanenko.github.io/hotrod-cli/#/contribution) before creating an issue or pull request.

## Licence

This project is open-sourced software licensed under the MIT license.