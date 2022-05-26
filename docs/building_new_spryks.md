## Building the new spryks


To build the new spryk you need to define spryke yml configuration in `config/spryk/spryks`. You need to define a spryk builder name into `spryk` option.
Depends on what builder is used the appropriate number of arguments you should to define.

### The spryks hierarchy structure

The spryk can depends on some other spryks and some other spryks should be executed before or after. It looks like a tree (or even a graph in some cases) structure that's controlled by
`preSpryks` and `postSpryks` configuration keys.

Is the best practice when spryk builds all the needed structures (classes, interfaces, configs and so on) as well. When someone executes the spryk he should get completely valid structure of files.
But in another hand the deep hierarchical structure hard to debug that is why try not to create deep structures and use only the required dependencies.

The arguments of the children spryks can inherit the values of the same arguments of the paren spryks. To achieve that you should define [`inherit: true`](/docs/spryk_configuration_reference.md#inherit) option in argument definition.

### The spryks overriding

The spryk overriding is useful for customisation the existing spryks in `preSpryks` or `postSpryks` by passing or overriding the arguments. You can even pass the arguments that are not defined in overridden spryk.
```yaml
postSpryks:
    - AddSharedTransferSchema # spryk is used as is
    - AddZedDependencyProvider: # spryk with overrided arguments
        arguments:
            extends:
                value: \Spryker\Zed\DataImport\DataImportDependencyProvider
```

Also you can manipulate `preSpryks` or `postSpryks` in spryks if you don't need the full spryk hierarchy.

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

### How to use spryk condition

Useful when you need to limit some spryk execution by some conditions. For example in one case you need to create one method in another case another method.
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
- Prefer usage of basic common sprykes (located in `config/spryk/spryks/Spryker/Common`) for re-usage
- As mentioned above try not to create the deep hierarchy of spryks
- Always populate spryks and arguments descriptions
- Try not to generate unused methods, classes, configs and so on. Use conditions to resolve it
