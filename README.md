# iserter/cakephp-postman-generator

A CakePHP plugin that generates a Postman collection (v2.1) by reading registered routes and controller actions.

## Installation

```bash
composer require iserter/cakephp-postman-generator
```

Copy or enable the plugin (depending on your CakePHP version):

```php
// In src/Application.php (for CakePHP 4+)
$this->addPlugin('Iserter/PostmanGenerator');
```

## Usage

Run the CLI command to generate `postman_collection.json` in your project root:

```bash
bin/cake iserter_postman generate
```

## Notes

- The package inspects routes via `Router::routes()` and tries to infer controller/action and HTTP methods.
- You can customize output path via config file `config/postman.php` if needed.

## Testing

After cloning the repository:

```bash
composer install
composer test
```

Static analysis & coding standards:

```bash
composer analyse
composer lint
```

Generate coverage (requires Xdebug or PCOV):

```bash
composer coverage
```

Tests are organized under `tests/TestCase` mirroring the `src` structure. The test bootstrap creates isolated temp directories and loads the plugin without needing a full CakePHP application skeleton.

