# Spryk configuration reference
___

All the Spryk definitions are located in the `config/spryk/spryks` directory.

Here is the example of the Spryk configuration:
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

In Spryk configuration, the following elements are used:

### spryk
This is name of the builder that is used for processing the Spryk. All the builders reside in `src/Spryk/Model/Spryk/Builder/`
and must implement `SprykerSdk\Spryk\Model\Spryk\Builder\SprykBuilderInterface::getName()`.

### description
Description of the Spryk. Must be added to give the reader of the Spryk definition a clear description of what this Spryk does.

### mode
The mode of the Spryk. It is used for running the specific Spryks by passing the `--mode` option to the command. The reserved `both` value allows to run Spryk in any case.

`mode: project` - Spryk is run only with `--mode=project` option in the CLI command.

`mode: core` - Spryk is run only with the `--mode=core` option in the CLI command.

`mode: both` - Spryk is run regardless of the `--mode` option value or without this option at all in the CLI command.


### level
Used only for Spryk dumper to dump the specific level of Spryks.
`vendor/bin/spryk-dump --level=1` or `vendor/bin/spryk-dump --level=all`.

### condition
Defines the condition of the Spryk execution. If condition is `false`, the Spryk execution is skipped with it's (pre)postSpryks. The arguments that are used in condition must be defined in the Spryk arguments list.

### arguments
The Spryk argument list. These arguments are used in the Spryk builder. See below [Arguments](#arguments) for details.

### preSpryks
The Spryks that should be executed before the current Spryk.

### spryks
The Spyrks to be execute in the current Spryk. This argument should be used if the current Spryk is not doing anything itself.

### postSpryks
The Spryks that should be executed after the current Spryk.

### excludedSpryks
Excludes the execution of the Spryks that are placed is `preSpryks` and `postSpryks`. Useful when you reuse a Spryk and you don't need some Spryks that are defined in `preSpryks` or `postSpryks`.

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
The argument value to be used. Useful when you need to compose a value from another argument values or apply some twig filters or functions.
```yaml
arguments:
    target:
      value: "{{ organization }}\\Glue\\{{ module }}\\Dependency\\Client\\{{ module }}To{{ dependentModule }}ClientBridge"

    name:
      value: "CLIENT_{{ dependentModule | underscored | upper }}"
```

### isOptional
If argument is optional, the values can be empty. Otherwise, the argument value should be provided. This option is `false` by default, which means the value is required.

### isMultiple
The argument can have multiple values, and a value can be provided as a list of values.
```yaml
arguments:
    target:
        isMultiple: true
```

### allowOverride
This option is only valid for the body argument of a Spryk method and defines whether you can override the existing method body.

```yaml
body:
  allowOverride: true
  value: "App/Registry/ZedControllerDisconnectMethod.php.twig"
```

### callback
The pre-processing callback that will be applied on value before passing to the Spryk. It should implement `SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback\CallbackInterface`.
