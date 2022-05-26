## Developer guides

### How to check and debug

To avoid dependency conflicts spryker-sdk/spryk-src is compiled into the phar archive then push to the spryker-sdk/spryk and this dependency is used for the target projects.
You can just recompile it everytime to check something.
But it's not suitable when you need constantly make some updates or debug it.
But you can install package into the vendor folder without adding the dependency via composer.json and test it directly on project files (on for development purposes).
```shell
cd vendor
git clone git@github.com:spryker-sdk/spryk-src.git
cd spryker-sdk/spryk-src
git checkout <your branch>
```

To run spryks you can use command from the cli or testing environments.
```shell
# from the host
SPRYKER_XDEBUG_ENABLE=1 docker/sdk testing
or
SPRYKER_XDEBUG_ENABLE=1 docker/sdk cli

#inside the docker
php vendor/spryker-sdk/spryk-src/bin/spryk <spryk name> <spryk arguments>

#ex.
php vendor/spryker-sdk/spryk-src/bin/spryk AddCrudFacade --mode project --organization Pyz --module Ay --domainEntity Entity -n
```

### How to disable/enable code sniffer

Before the writing all the files into the project CS fix all the issues related to code style and import missed namespaces into the use statements.
That is why we need to use the FQCNs everywhere(templates, spryks arguments and so on).
To check what the files were generated without the affecting the code sniffer need to pass `TESTING=true` env variable into the command also the code compiling much more faster without CS.
```shell
TESTING=true php vendor/spryker-sdk/spryk-src/bin/spryk <spryk name> <spryk arguments>
```

### What is `FileResolver`

`\SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\FileResolverInterface` - this is the core part of file management into the spryk-src.
It collects all the updated or created files and then flushes them into the filesystem at the end of the command execution.
All the new or updated files should always be added into the FileResolver.
Normally you shouldn't work with file system directly by php functions like `file_put_contents`, `file_get_contents`, `file_exists` and so on.
You should use `FileResolverInterface::hasResolved`, `FileResolverInterface::resolve`, `FileResolverInterface::addFile`.
**Never use `resolve` or `addFile` for the core files or namespaces. You can just check them by `FileResolverInterface::hasResolved`!**

### How to get the file path

To get the target file path need to use these methods.
`\SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder::getTargetPath`
`\SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder::getFileTargetPath`
`\SprykerSdk\Spryk\Model\Spryk\Builder\AbstractBuilder::getAbsoluteTargetPath`

In testing environment these methods returns virtual filesystem root path.





