# PHAR Compiler for Spryk

## Compile the PHAR

```bash
composer install
php bin/prepare
```

The compiled PHAR will be in `tmp/spryk.phar`.

Please note that running the compiler will change the contents of `composer.json` file and `vendor` directory. Revert those changes after running it.
