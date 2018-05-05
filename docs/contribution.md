# Contribution

Help is very welcomed. Code contributions must be done in respect of [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
To fix the [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) issues You can run

```
    vendor/bin/php-cs-fixer fix ./src
    vendor/bin/php-cs-fixer fix ./tests
```

## Issues 

Bugs & Feature requests: If you found a bug, open an issue on [Github](https://github.com/dmitrijivanenko/hotrod-cli/issues). Please search for already reported similarly issues before opening a new one.

## Setup

If You want to make some developing changes, You need to setup a dev environment for that. 
You don not need a Magento 2 codebase or real project for that. Clone the project source to your directory, and run

```
   cp .env.example .env 
```
    
It will set the project to development mode, it means it will generate the code just in project root folder - `./app/code`. 
So You can easily check the result of your changes.

## Codding

### Rules

* Pull requests are made to master. Changes are never pushed directly (without pull request) into master.
* We use the [Forking Workflow](https://www.atlassian.com/git/tutorials/comparing-workflows/forking-workflow).
* Use a feature branch for every pull request. Don't open a pull request from your master branch.

### Pull Requests

* One change per pull requests: Keep your pull requests as small as possible. Only one change should happen per pull request. 
This makes it easier to review and provided feedback. If you have a large pull request, try to split it up in multiple smaller requests.
* Tests: Your addition / change must be tested and the builds must be green. Test your changes locally. Add unit tests and if possible functional tests.
