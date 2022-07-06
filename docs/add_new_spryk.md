## Adding a new Spryk

To add a new Spryk, you need to add a YAML configuration to the `config/spryk/spryks/` directory.

The `spryk` option defines which builder is used to work with the defined Spryk definition.

For example, `spryk: Template` uses the `SprykerSdk\Spryk\Model\Spryk\Builder\Template\TemplateSpryk` class to fulfill the Spryk definition.

### The Spryks hierarchy structure

Spryks can depend on other Spryks that are executed before or after the current Spryk is executed. The Spryks hierarchy structure resembles a tree (or even a graph in some cases) structure that's controlled by
`preSpryks` and `postSpryks` configuration keys.

The best practice is when a Spryk builds only one small structural unit and calls other Spryks that build the rest of the structure with classes, interfaces, configs, and so on. When executing a Spryk, one should get a completely valid structure of files.

The arguments of the children's Spryks can inherit the values of the same arguments of the parent Spryk. To achieve that you need to define the [`inherit: true`](/docs/spryk_configuration_reference.md#inherit) option in the argument definition block.

### Overriding arguments in pre/postSpryks

Overriding is useful for the customization of a used Spryk in the `preSpryks` or `postSpryks` section. You can pass or override arguments here.

You can even add arguments that are not defined in the used Spryk itself. This is useful primarily when you use a Twig template with more arguments than the original used template has.
```yaml
postSpryks:
    - AddSharedTransferSchema # Spryk is used as is
    - AddZedDependencyProvider: # Spryk with overrided arguments
        arguments:
            extends:
                value: \Spryker\Zed\DataImport\DataImportDependencyProvider
```

As explained earlier, you can look at Spryks as some sort of hierarchy where one Spryk uses several other Spryks. In some cases, you don't need the full hierarchy. To exclude the execution of one or more Spryks you can use the `excludedSpryks` option.

```yaml
postSpryks:
    - AddZedCommunicationControllerAction:
          excludedSpryks:
              - AddZedPresentationTwig
              - AddZedNavigationNode
          arguments:
              allowOverride:
                  value: true
              input:
                  value: "\\Symfony\\Component\\HttpFoundation\\Request $request"
              output:
                  value: "\\Symfony\\Component\\HttpFoundation\\JsonResponse"
              controller:
                  value: RegistryController
              controllerMethod:
                  value: disconnect
              body:
                  allowOverride: true
                  value: "App/Registry/ZedControllerDisconnectMethod.php.twig"
```

We also have three syntax options for overriding the definition of spryks in the `preSpryks`/`postSpryks` sections:
- With a `value` key for use with any additional configuration.
- Without a value key, if the argument has only a `value` without anything else.
- Without `value`, but with keys for configuration.

Example:

```yaml
postSpryks:
    - AddZedPersistenceRepositoryMethod:
          arguments:
              repositoryMethod:
                  value: "apply{{ domainEntity }}Sorting"
              visibility: "protected"
              input:
                  - "\\Orm\\Zed\\{{ module }}\\Persistence\\Spy{{ domainEntity }}Query ${{ domainEntity | lcfirst }}Query"
                  - "\\Generated\\Shared\\Transfer\\{{ domainEntity }}CriteriaTransfer ${{ domainEntity | lcfirst }}CriteriaTransfer"
              output: "\\Orm\\Zed\\{{ module }}\\Persistence\\Spy{{ domainEntity }}Query"
              domainEntity:
                  inherit: true
              body:
                  allowOverride: true
                  value: Zed/Persistence/Repository/DomainEntity/ApplySorting.php.twig
```

### Conditional Spryks

In some cases, you need to run a Spryk only when a specific condition is matched. For example one of the passed arguments has a specific value. For these cases, you can use the `condition` option.
For condition evaluation `symfony/expression-language` component is used.

```yaml
postSpryks:
    # Add a factory method for non Zed applications.
    - AddMethod:
          condition: "application !== 'Zed'"
          arguments:
              method:
                  value: "get{{dependentModule | ucfirst }}{{ dependencyType | ucfirst }}"
              ...
    # Add a factory method for Zed applications.
    - AddMethod:
          condition: "application === 'Zed'"
          arguments:
              method:
                  value: "get{{dependentModule | ucfirst }}{{ dependencyType | ucfirst }}"
              ...
```
### The wrapper Spryk

To merge some Spryks into a bigger structure to enable execution of all of them with a command, you can use a wrapper Spryk.
This Spryk only executes `preSpryks` or `postSpryks` and receives the arguments.

```yaml
spryk: wrapper
description: "Adds a registry code for apps. Builds the logic for connection and disconnection."
priority: 1
mode: both
level: 1

arguments:
    organization:
        inherit: true
        default: Spryker

    application:
        inherit: true
        default: Zed

excludedSpryks:
    - AddZedPresentationTwig
    - AddZedNavigationNode

preSpryks:
    - AddModule

postSpryks:
    - AddZedBusinessModelMethod:
          arguments:
              allowOverride:
                  value: true
  ...
```

### The best practices

- Try not to create your own Spryks unless you're sure that you can not customize some existing Spryk.
- Prefer the basic common Spryks (located in `config/spryk/spryks/Spryker/Common/), for re-use.
- As mentioned earlier, try not to create the deep hierarchy of Spryks.
- Always populate Spryks and argument descriptions.
- Try not to generate unused methods, classes, configs, etc. Use `conditions` option to resolve it.
