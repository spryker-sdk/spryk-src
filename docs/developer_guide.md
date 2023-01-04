## Developer guides

### How to check and debug Spryks

To avoid dependency conflicts, `spryker-sdk/spryk-src` is compiled into a `phar` archive. The archive is then pushed to `spryker-sdk/spryk` and included as a dependency into the target project.
You have to recompile `spryker-sdk/spryk-src` every time when you need to check something. However, this approach  is not suitable when you constantly need to make some updates and debug.
You can install the package into the vendor folder without adding the dependency to `composer.json` and test it directly on project files for development purposes:
```shell
cd vendor/spryker-sdk
git clone git@github.com:spryker-sdk/spryk-src.git
cd spryk-src
git checkout <your branch>
composer install
```

To run Spryks, you can use a command from the CLI or testing environments:
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

### How to enable and disable the Code Sniffer

Before the writing all the files into a project, the [Code Sniffer](https://docs.spryker.com/docs/scos/dev/sdk/development-tools/code-sniffer.html) fixes all the code style issues and imports the missing namespaces. For this reason, use the FQCNs everywhere (templates, arguments, etc.).
To check how files look after they have been generated without running the Code Sniffer, disable the Code Sniffer by prefixing the console command with `TESTING=true`:
```shell
TESTING=true php vendor/spryker-sdk/spryk-src/bin/spryk <Spryk name> <Spryk arguments>
```
This is the good practice firstly check raw generated code without any post-processing also the commands are executed much faster.

### FileResolver

The file resolver, located in `SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface`, is the core part of the file management.
It collects all the updated or created files and flushes them into the filesystem at the end of the Spryk command execution.
All the new or updated files must always be added into the FileResolver.
Normally you shouldn't work with file system directly by PHP functions like `file_put_contents`, `file_get_contents`, `file_exists` and so on.
You must use `FileResolverInterface::hasResolved()`, `FileResolverInterface::resolve()`, `FileResolverInterface::addFile()`.
**Never use `resolve` or `addFile` for the core files or namespaces. You can just check these files or namespaces by `FileResolverInterface::hasResolved`!**

### How to get the file path

You can get the absolute path from the `targetPath` argument:
`SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder::getTargetPath()`
You can also get the absolute path from the relative path:
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

In testing environment, these methods return the virtual filesystem root path.





