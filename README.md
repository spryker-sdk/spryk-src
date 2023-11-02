# Spryk
[![CI](https://github.com/spryker-sdk/spryk-src/workflows/CI/badge.svg?branch=master)](https://github.com/spryker-sdk/spryk-src/actions?query=workflow%3ACI+branch%3Amaster)
[![Latest Stable Version](https://poser.pugx.org/spryker-sdk/spryk-src/v/stable.svg)](https://packagist.org/packages/spryker-sdk/spryk-src)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg)](https://php.net/)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)

## Installation

```
composer require --dev spryker-sdk/spryk-src
```

you can also use `git clone`.

This is a development only "require-dev" library. Please make sure you include it as such.

> **_NOTE:_**
>
> This repository is a development only package and will not be installed in projects directly.
>
> This package contains all code that will be compiled into a `spryk.phar` which will be installed instead.
>
> See [Compile](#Compile) section for further details.

### What are Spryks?

Spryks are some sort of code generators for Spryker. Writing code is often a very repetitive task and you often need to write a lot code just to follow Spryker's clean and complex architecture.
To take a way the monkey work from writing wir up code and move faster towards writing business code Spryks are born.

Spryks are written with the help of yml files. The filename of the yml file represents also the Spryk name. In most cases the Spryk yml contains arguments which are needed to fullfill the Spryk build run. Almost all Spryks need the module name to run properly. Some Spryks require much more arguments.

The vast majority of the Spryks need to execute other Spryks before the called Spryk can run. For example Add a Zed Business Facade needs to have a properly created module before the Facade itself can be created. Therefore Spryks have pre and post Spryks and with the call of one Spryk many things can and will be created for you.

### How to create a Spryk?

In most cases it is very easy to create a Spryk. As the whole Spryk Tool is covered by tests you also have to start by adding a test for the Spryk you want to create.

If you only need to add a new Spryk configuration you will start by adding an Integration test for the new Spryk definition. You need to add the name of the Spryk you want to test. E.g. AddMySuperNiceFile and add the assertion to have this file created after you executed the test.

When this is done run the Integration tests with `vendor/bin/codecept run Integration -g {YOUR TEST GROUP}` and see the test failing. You will get a message that the Spryk definition was not found by the given name, so add the definition file for you new Spryk.

You need to add your Spryk definition file into `config/spryk/spryks/` on project or core level:

```
project OR package root directory
│
└─── config/
│   └─── spryk/
│   │    └─── spryks/
│   │         │   ...
│   │         │   spryk-name.yml
│   │         │   ...
│   └─── ...
```

If you selected the template Spryk, you will most likely see the error that the defined template file could not be found. In this case you need to add your template to `config/spryk/templates/` on project or core level:

```
project OR package root directory
│
└─── config/
│   └─── spryk/
│   │    └─── templates/
│   │         │   ...
│   │         │   template-name.twig
│   │         │   ...
│   └─── ...
```

When this is done re-run your tests. Now you should see a green test.

### Compile

> **_NOTE_**
>
> You need to have [BOX](https://github.com/box-project/box) installed to create the `spryk.phar` archive.
>
> `spryker-sdk/spryk` and `spryker-sdk/spryk-src` need to be installed together.

To compile the `spryk.phar` you need to run the following steps:

- `composer update`
- `bin/console spryk:compile`

This will install the latest dependencies, create a fresh cache and compiles the archive.

### Console commands

#### Run Spryks

Runs a Spryk build process.

```shell
bin/spryk [options] [--] <spryk> [<targetModule> [<dependentModule>]]
#or
php bin/spryk-run [options] [--] <spryk> [<targetModule> [<dependentModule>]]
```

`spryk` Name of a specific Spryk to build.

Options:
- `--dry-run` Only prints a diff, doesn't change the files.
- `--include-optional=INCLUDE-OPTIONAL` Name(s) of the Spryks which are marked as optional but should be build.

#### Dump Spryks

Dumps a list of all Spryk definitions.

```shell
php bin/spryk-dump [options] [--] [<spryk>...]
```
`spryk` Name of a specific Spryk for which the options should be dumped.

Options:
- `--level=LEVEL` Spryk visibility level (1, 2, 3, all). By default = 1 (main spryk commands).

#### Build Spryks

Builds a cache for all possible Spryk arguments. This command must only be used if a new argument was supplied.

```shell
php bin/spryk-build
```

### See more
- [Spryk configuration reference](/docs/spryk_configuration_reference.md)
- [Developer's guide](/docs/developer_guige.md)
- [Add a new Spryk](/docs/add_new_spryk.md)
