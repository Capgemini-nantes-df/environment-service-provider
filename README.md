# EnvironmentServiceProvider

An environment ServiceProvider for [Silex](http://silex.sensiolabs.org) which
determines an Silex based applications runtime environment in different ways.

## Installation

Add the following requirement to your `composer.json`:
    {
        "required": {
            "simblo/environment-service-provider": "~1.0"
        }
    }

## Usage

### Register EnvironmentServiceProvider

Just use the Applications register method:
    $app->register(new EnvironmentServiceProvider();

### Configure EnvironmentServiceProvider

This provider takes an array with the following arguments as options:

`default`:
Defaults to development. Use `default` to set a fallback environment. If
something goes wrong or no environment is set properly the application will fall
back to the environment set here. This MUST be a valid environment (must be
present in `environments`).

If `default` is set to false the provider will throw an exception if no app
environment is properly set.

`filepath`:
Defaults to false which disables the filecheck. If you plan to set your app
environment through a file it must be set properly to contain the path this
file is in. **No trailing slash and do not include .setenv / filename in path**

`filename`:
Defaults to .setenv but can be set to any valid filename. If for what reason
ever you need to change the filename the provider is looking in for the environment.
If the file is not found the check is not successfull and the provider will use
the environment variable instead. If the file is found but empty the provider will
either fall back to `default` or throw an Exception if `default` is false.

`variable`:
Defaults to SILEX_ENVIRONMENT but can be changed to any valid environment variable
name. Maybe you wan't to use your own variable representing your applications name.

`environments`:
Defaults to development, testing, staging and production. Ensures no faulty
environment gets set and hopefully helps if you have a typo in your environment
variable or file.

To use any or all of this options just add them in an array which is given to
the provider while registering it:
    $app->register(new EnvironmentServiceProvider(array(
        'default' => 'bar',
        'filepath' => '/your/config/dir',
        'filename' => 'thefile.bla.lol',
        'variable' => 'MIGHTY_ENVIRONMENT_VARIABLE',
        'environments' => array('foo', 'bar'),
    )));

### Setting an environment

An environment can be set in two ways. Either there can be an environment variable:

Set it in your .htaccess:
    SetEnv SILEX_ENVIRONMENT production

Set in in index.php before the provider is registered:
    <?php
    putenv('SILEX_ENVIRONMENT=production');

Set it on shell before calling a cli application:

SILEX_ENVIRONMENT=production php cli.php

If you are not able to set an environment variable a file containing a string
representing the desired environment can be created.

Create a file named `.setenv` anywhere PHP can read it and write a single line
with the runtime environment you need. Set the appropriate options while you
register the provider:
    $app->register(new EnvironmentServiceProvider(array(
        'filepath' => '/path/to/your/.setenv'
    )));

**Again: No trailing slash and do not include .setenv / filename in path**

### Retrieving the environment
If everything worked as intended you can get the actual environment by accessing
`$app['environment']` anywhere your application instance is present. Do whatever
you wan't with it :)