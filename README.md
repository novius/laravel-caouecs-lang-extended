# Novius Caouecs lang extended

This package gives an artisan command to publish native laravel translations in the desired language.
The native translations are made by the [caouecs/laravel-lang package](https://github.com/caouecs/Laravel-lang).

## Installation

You can install the package via Composer:

```
composer install novius/caouecs-lang-extended
```
* For laravel 5.6, 5.7, 5.8 use 0.4
* For laravel 5.4, 5.5 use 0.3

Then, if you are on Laravel 5.4 (no need for Laravel 5.5 and higher), register the service provider to your `config/app.php` file:

```
'providers' => [
    ...
    Novius\Caouecs\Lang\LangGeneratorServiceProvider::class,
];
```

## Usage

This command put languages files into `resources/lang/{local}` folder. 

```
php artisan lang:install {local}
```

Example for publishing FR files : 

```
php artisan lang:install fr
```

By default the command does not overwrite existing files. You can use `--force` parameters to force overriding.

```
php artisan lang:install fr --force
```

## Testing

Run the tests with:

```
./test.sh
```


## Lint

Run php-cs with:

```
./cs.sh
```

## Contributing

Contributions are welcome!

## Licence

This package is under [GNU Affero General Public License v3](http://www.gnu.org/licenses/agpl-3.0.html) or (at your option) any later version.
