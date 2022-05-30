## Add a new Spryk

To add a new Spryk you need to add a YAML configuration in the `config/spryk/spryks/` directory.

The `spryk` option defines which builder is used to work with the defined Spryk definition.

Example: `spryk: Template` will use the `SprykerSdk\Spryk\Model\Spryk\Builder\Template\TemplateSpryk` class to fulfill the Spryk definition.

### The spryks hierarchy structure

Spryks can depend on other Spryks that will be executed before or after the current Spryk is executed. It looks like a tree (or even a graph in some cases) structure that's controlled by
`preSpryks` and `postSpryks` configuration keys.

A best practice is when a Spryk builds only one small structural unit and calls other Spryks that build all rest of the needed structures (classes, interfaces, configs, and so on). When someone executes a Spryk he should get a completely valid structure of files.

The arguments of the children's Spryks can inherit the values of the same arguments of the parent Spryk. To achieve that you need to define the [`inherit: true`](/docs/spryk_configuration_reference.md#inherit) option in the argument definition block.

### Overriding arguments in pre/postSpryks

Overriding is useful for the customization of a used Spryk in the `preSpryks` or `postSpryks` section. You can pass or override arguments here.
You can even add arguments that are not defined in the used Spryk itself. This is mainly useful when you use a Twig template with more arguments than the original used template has.
```yaml
postSpryks:
    - AddSharedTransferSchema # spryk is used as is
    - AddZedDependencyProvider: # spryk with overrided arguments
        arguments:
            extends:
                value: \Spryker\Zed\DataImport\DataImportDependencyProvider
```

As explained before, you can look at Spryks as some sort of hierarchy where one Spryk uses several other Spryks. In some cases, you don't need the full hierarchy. To exclude the execution of one or more Spryks you can use the `excludedSpryks` option.

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
### The wrapper spryk

If you need to union or compose some sprykes into the some bigger structure to make possible execute all of them by the command wrapper spryk can be used.
This spryk do nothing except the execution of `preSpryks` or `postSpryks` and receiving the arguments.

```yaml
spryk: wrapper
description: "Adds a Registry code for Apps. It builds connect and disconnect logic."
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

- Try not to create the own spryks until you're sure that you can not customise some existing spryk
- Prefer usage of basic common sprykes (located in `config/spryk/spryks/Spryker/Common/`) for re-usage
- As mentioned above try not to create the deep hierarchy of spryks
- Always populate spryks and arguments descriptions
- Try not to generate unused methods, classes, configs and so on. Use `conditions` option to resolve it
