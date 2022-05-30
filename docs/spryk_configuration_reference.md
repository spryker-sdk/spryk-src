# Spryk configuration reference
___

All the spryks configs are located in `config/spryk/spryks` directory.

Example of spryk configuration.
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
This is name of the builder that is used for processing this spryk. All the builders can be found in `src/Spryk/Model/Spryk/Builder/`
and should implement `SprykerSdk\Spryk\Model\Spryk\Builder\SprykBuilderInterface::getName()`.

### description
Description of the spryk. Should be populated for every spryk for the informational purposes.

### mode
The mode of the spryk. It used for running the specific spryks by passing the `--mode` option into the command. The reserved `both` value allows to run spryk in any case.

### level
Is used only for spryk dumper to dump the specific level of spryks
`vendor/bin/spryk-dump --level=1` or `vendor/bin/spryk-dump --level=all`.

### condition
Defines the condition of the spryk execution. If condition evaluates to false the spryk execution will be skipped with it's (pre)postSpryks. The arguments that are used in condition should be defined in spryk arguments list.

### arguments
The spryk argument list. These arguments are used in the spryk builder. See below for more detailed description.

### preSpryks
The sprykes that should be executed before the current spryk.

### postSpryks
The sprykes that should be executed after the current spryk.

### excludedSpryks
Exclude the execution of the sprykes that are placed is `preSpryks` and `postSpryks`. Useful when you reuse the spryk and you don't need some spryks that are defined in `preSpryks` or `postSpryks`.

### preCommands
The commands that should be executed before the current spryk.

### postCommands
The commands that should be executed after the current spryk.

## Arguments

```yaml
arguments:
    organization: # argument name
        inherit: true
        default: Spryker
```

### inherit
Declares that the argument values can be passed from the parent spryk.

### default
The argument default value if not set.

### value
The argument value that can be set. Useful when need to compose value from another arguments values or/and apply some twig filters or functions.
```yaml
arguments:
    target:
      value: "{{ organization }}\\Glue\\{{ module }}\\Dependency\\Client\\{{ module }}To{{ dependentModule }}ClientBridge"

    name:
      value: "CLIENT_{{ dependentModule | underscored | upper }}"
```

### isOptional
If argument is optional the values can be empty otherwise the argument value should be provided.

### isMultiple
The argument can have multiply values and value can be provided as list of values.
```yaml
arguments:
    target:
      value:
          - "one"
          - "two"
          - "three"
```

### allowOverride
This option is only valid for the body argument of method spryk and define you can or not to override existing method body.

```yaml
body:
  allowOverride: true
  value: "App/Registry/ZedControllerDisconnectMethod.php.twig"
```

### callback
The pre-processing callback that will be applied on value before passing into the spryk. Should implement `SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback\CallbackInterface`.
