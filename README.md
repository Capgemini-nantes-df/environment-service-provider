# EnvironmentServiceProvider

An environment ServiceProvider for [Silex](http://silex.sensiolabs.org) which
determines the environment (e.g. development, testing or production) an
application build around [Silex](http://silex.sensiolabs.org) is running in.


[![Latest Stable Version](https://poser.pugx.org/simblo/environment-service-provider/v/stable.png)](https://packagist.org/packages/simblo/environment-service-provider)
[![Latest Unstable Version](https://poser.pugx.org/simblo/environment-service-provider/v/unstable.png)](https://packagist.org/packages/simblo/environment-service-provider)
[![Build Status](https://travis-ci.org/simblo/environment-service-provider.png?branch=master)](https://travis-ci.org/simblo/environment-service-provider)
[![Coverage Status](https://coveralls.io/repos/simblo/environment-service-provider/badge.png?branch=master)](https://coveralls.io/r/simblo/environment-service-provider?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/52cedab4ec13752ce20000ae/badge.png)](https://www.versioneye.com/user/projects/52cedab4ec13752ce20000ae)

## Installation

Installing EnvironmentServiceProvider is as simple as adding the following
requirement to your `composer.json` file:

```json
{
    "required": {
        "simblo/environment-service-provider": "~1.0"
    }
}
```

## Usage

### Register EnvironmentServiceProvider

EnvironmentServiceProvider can be registered through the `register()` method of
`\Silex\Application` as any other service provider. Just add this line of code
after creating the application instance:

```php
$app->register(new EnvironmentServiceProvider());
```

### Configure EnvironmentServiceProvider

While creating a new instance of the provider you can pass an optional array
with configuration data like this...

```php
$app->register(new EnvironmentServiceProvider(array(
    'default' => 'bar',
    'filepath' => '/your/config/dir',
    'filename' => 'thefile.bla.lol',
    'variable' => 'MIGHTY_ENVIRONMENT_VARIABLE',
    'environments' => array('foo', 'bar'),
)));
```

The example above also shows all possible options that can be used to configure
EnvironmentServiceProvider to your needs. Here are the options explained in detail.

#### default

Defaults to *development*. The `default` option can be used to set a fallback
environment if the detection fails or you did not set an environment on purpose.
`default` must be a valid environment which is present in `environments`

If `default` is set to false EnvironmentServiceProvider will throw an exception
instead of falling back to a default environment if no environment is detected.

#### filepath

Defaults to *false*. As long as `filepath` is set to false the provider will not
check for an environment file which contains the environment the application
should run in. If this is set to an appropriate path containing the environment
file it's content is used as environment as long as it is a valid environment
present in `environments`. *No trailing slash and do not include the filename of
the environment file* since he is set in the next option.

#### filename

Defaults to *.setenv*. This option represents the name of the file containing
the environment the application should run in. Change this if you do not like
the default setting or can't use it for any reason.

#### variable

Defaults to SILEX_ENVIRONMENT. If no `filepath` is set or the environment file
was not readable the provider uses an environment variable to determine the
application environment. If for any reason you can't use the default value or
feel that it does not fit your application feel free to change this option.

#### environments

Defaults to development, testing, staging and production. The application can
only run in environments known by the provider. This option is an array with
strings representing known or valid enviromnents the application is allowed to
run in. The default value should fit any needs but maybe your application is a
special one ;)

### Setting an environment

To set an application environment the provider is able to determine there are
two different possible methods.

This can either be achieved by setting an environment variable named 
`SILEX_ENVIRONMENT` like shown in the following examples

####.htaccess:

```apacheconf
SetEnv SILEX_ENVIRONMENT production
```

#### PHP

```php
putenv('SILEX_ENVIRONMENT=production');
```

or it can be set in an environment file. In this case a valid `filepath` must
be set in the options. Just create the file `.setenv` in a location which can
be accesses by PHP and write down the desired environment inside.

### Retrieving the environment

Now you are able to access the environment by using the `environment` property
of your Silex Application like this:

```php
$env = $app['environment']
```