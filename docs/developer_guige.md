## Developer guides

### How to check and debug

To avoid dependency conflicts spryker-sdk/spryk-src is compiled into the phar archive then is pushed to the spryker-sdk/spryk and is included as dependency into the target project.
You have to recompile it everytime to check something but it's not suitable when you constantly need to make some updates and debug it.
You can install package into the vendor folder without adding the dependency in composer.json and test it directly on project files (on for development purposes).
```shell
cd vendor/spryker-sdk
git clone git@github.com:spryker-sdk/spryk-src.git
cd spryk-src
git checkout <your branch>
composer install
```

To run Spryks you can use command from the cli or testing environments.
```shell
# from the host
SPRYKER_XDEBUG_ENABLE=1 docker/sdk testing
or
SPRYKER_XDEBUG_ENABLE=1 docker/sdk cli

#inside the docker
php vendor/spryker-sdk/spryk-src/bin/spryk <Spryk name> <Spryk arguments>

#ex.
php vendor/spryker-sdk/spryk-src/bin/spryk AddCrudFacade --mode project --organization Pyz --module Ay --domainEntity Entity -n
```

### How to disable/enable code sniffer

Before the writing all the files into the project `codeSniffer` fix all the code style issues and import missed namespaces, that's why we need to use the FQCNs everywhere(templates, arguments and so on).
To check how files look after generating them without running the CodeSniffer you need to disable the CodeSniffer by prefixing the console command with `TESTING=true`.
```shell
TESTING=true php vendor/spryker-sdk/spryk-src/bin/spryk <Spryk name> <Spryk arguments>
```
This is the good practice firstly check raw generated code without any post-processing also the commands are executed much faster.

### What is `FileResolver`

`SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface` - this is the core part of file management.
It collects all the updated or created files and then flushes them into the filesystem at the end of the command execution.
All the new or updated files must always be added into the FileResolver.
Normally you shouldn't work with file system directly by php functions like `file_put_contents`, `file_get_contents`, `file_exists` and so on.
You must use `FileResolverInterface::hasResolved()`, `FileResolverInterface::resolve()`, `FileResolverInterface::addFile()`.
**Never use `resolve` or `addFile` for the core files or namespaces. You can just check them by `FileResolverInterface::hasResolved`!**

### How to get the file path

Get absolut path from `targetPath` argument:
`SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder::getTargetPath()`
Get absolut path from relative path:
`SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder::getFileTargetPath(string $relativeFilePath)`

```php
class SomeSpryk extends AbstractBuilder
{
    // ...

    /**
     * @return void
     */
    protected function build(): void
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedXmlInterface $resolved */
        $resolved = $this->fileResolver->resolve($this->getTargetPath());

        // ...
    }
}
```


In testing environment these methods returns virtual filesystem root path.





