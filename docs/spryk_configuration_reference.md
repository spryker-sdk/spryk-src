# Spryk configuration reference
___

All the Spryk definitions are located in the `config/spryk/spryks` directory.

Example of Spryk configuration.
```yaml
spryk: wrapper
description: "Adds CRUD code for a Domain Entity."
condition: "organization === 'Pyz'"
mode: both
level: 2

arguments:
    organization:
        inherit: true
        default: Spryker

excludedSpryks:
    - AddZedPresentationTwig

postSpryks:
    - AddZedDomainEntityDeleter
```

Spryk name is defined by its filename.

## The root configuration

### spryk
This is name of the builder that is used for processing this Spryk. All the builders can be found in `src/Spryk/Model/Spryk/Builder/`
and must implement `SprykerSdk\Spryk\Model\Spryk\Builder\SprykBuilderInterface::getName()`.

### description
Description of the Spryk. Must be added to give the reader of the Spryk definition a clear description of what this Spryk is doing.

### mode
The mode of the Spryk. It used for running the specific Spryks by passing the `--mode` option into the command. The reserved `both` value allows to run Spryk in any case.

`mode: project` - Spryk will be run only with `--mode=project` option in CLI command.

`mode: core` - Spryk will be run only with `--mode=core` option in CLI command.

`mode: both` - Spryk will be run regardless the `--mode` option value or without this option at all in CLI command.


### level
Is used only for Spryk dumper to dump the specific level of Spryks
`vendor/bin/spryk-dump --level=1` or `vendor/bin/spryk-dump --level=all`.

### condition
Defines the condition of the Spryk execution. If condition evaluates to false the Spryk execution will be skipped with it's (pre)postSpryks. The arguments that are used in condition must be defined in Spryk arguments list.

### arguments
The Spryk argument list. These arguments are used in the Spryk builder. See below for more detailed description.

### preSpryks
The Spryks that will be executed before the current Spryk.

### postSpryks
The Spryks that should be executed after the current Spryk.

### excludedSpryks
Exclude the execution of the Spryks that are placed is `preSpryks` and `postSpryks`. Useful when you reuse the Spryk and you don't need some Spryks that are defined in `preSpryks` or `postSpryks`.

### preCommands
The commands that should be executed before the current Spryk.

### postCommands
The commands that should be executed after the current Spryk.

## Arguments

```yaml
arguments:
    organization: # argument name
        inherit: true
        default: Spryker
```

### inherit
Declares that the argument value can be inherited from the parent Spryk when not set explicitly.

### default
The default value for the argument if not passed from CLI.

### value
The argument value that will be used. Useful when need to compose value from another arguments values or/and apply some twig filters or functions.
```yaml
arguments:
    target:
      value: "{{ organization }}\\Glue\\{{ module }}\\Dependency\\Client\\{{ module }}To{{ dependentModule }}ClientBridge"

    name:
      value: "CLIENT_{{ dependentModule | underscored | upper }}"
```

### isOptional
If argument is optional the values can be empty otherwise the argument value should be provided. This option is `false` by default which means the value is required.

### isMultiple
The argument can have multiply values and value can be provided as list of values.
```yaml
arguments:
    target:
        isMultiple: true
```

### allowOverride
This option is only valid for the body argument of method Spryk and define you can or not to override existing method body.

```yaml
body:
  allowOverride: true
  value: "App/Registry/ZedControllerDisconnectMethod.php.twig"
```

### callback
The pre-processing callback that will be applied on value before passing into the Spryk. Should implement `SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback\CallbackInterface`.
