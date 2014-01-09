<?php
/**
 * This file is part of EnvironmentServiceProvider for Silex
 * 
 * @link https://github.com/simblo/environment-service-provider EnvironmentServiceProvider
 * @copyright (c) 2014, Holger Braehne
 * @license http://raw.github.com/simblo/environment-service-provider/master/LICENSE MIT
 */

use Silex\Application;
use Simblo\Silex\Provider\EnvironmentServiceProvider;

/**
 * EnvironmentServiceProviderTest
 * 
 * @author Holger Braehne <holger.braehne@simblo.org>
 * @since 1.0.0
 */
class EnvironmentServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterWithoutOptions()
    {
        $app = new Application();
        $app->register(new EnvironmentServiceProvider());
        
        $this->assertSame('development', $app['environment']);
    }
    
    public function testWithDefaultOption()
    {
        $app = new Application();
        $app->register(new EnvironmentServiceProvider(array('default' => 'production')));
        
        $this->assertSame('production', $app['environment']);
    }
    
    public function testWithDefaultOptionToFalse()
    {
        $this->setExpectedException('Simblo\Silex\Exception\NoEnvironmentSetException', 'No valid runtime environment was set.');
        
        $app = new Application();
        $app->register(new EnvironmentServiceProvider(array('default' => false)));
    }
    
    public function testWithFilepathOption()
    {
        $app = new Application();
        $app->register(new EnvironmentServiceProvider(array(
            'filepath' => __DIR__ . '/Fixtures'
        )));
        
        $this->assertSame('testing', $app['environment']);
    }
    
    public function testWithFilenameOption()
    {
        $app = new Application();
        $app->register(new EnvironmentServiceProvider(array(
            'filepath' => __DIR__ . '/Fixtures',
            'filename' => '.environment'
        )));
        
        $this->assertSame('staging', $app['environment']);
    }

    public function testWithBrokenFileContent()
    {
        $app = new Application();
        $app->register(new EnvironmentServiceProvider(array(
            'filepath' => __DIR__ . '/Fixtures',
            'filename' => '.broken'
        )));
        
        $this->assertSame('staging', $app['environment']);
    }
    
    public function testWithEnvironmentsOption()
    {
        $app = new Application();
        $app->register(new EnvironmentServiceProvider(array(
            'default' => 'foo',
            'environments' => array('foo', 'bar', 'baz')
        )));

        $this->assertSame('foo', $app['environment']);
    }
    
    public function testWithAllOptions()
    {
        $app = new Application();
        $app->register(new EnvironmentServiceProvider(array(
            'default' => 'bar',
            'filepath' => false,
            'filename' => '.none',
            'environments' => array('foo', 'bar', 'baz')
        )));
        
        $this->assertSame('bar', $app['environment']);
    }
    
    public function testWithNotAllowedEnvironment()
    {
        $app = new Application();
        $app->register(new EnvironmentServiceProvider(array(
            'filepath' => __DIR__ . '/Fixtures',
            'filename' => '.notallowed'
        )));
        
        $this->assertSame('development', $app['environment']);
    }
    
    public function testWithEnvironmentVariable()
    {
        putenv('SILEX_ENVIRONMENT=staging');
        
        $app = new Application();
        $app->register(new EnvironmentServiceProvider());
        
        $this->assertSame('staging', $app['environment']);
    }
    
    public function testWithVariableOption()
    {
        putenv('FOO_VAR=staging');
        
        $app = new Application();
        $app->register(new EnvironmentServiceProvider(array('variable' => 'FOO_VAR')));
        
        $this->assertSame('staging', $app['environment']);
    }
    
    public function testWithNotAllowedEnvironmentInVariable()
    {
        putenv('SILEX_ENVIRONMENT=foo');
        
        $app = new Application();
        $app->register(new EnvironmentServiceProvider());
        
        $this->assertSame('development', $app['environment']);
    }
    
    public function testWithNotAllowedEnvironmentInVariableAndDefaultToFalse()
    {
        $this->setExpectedException('Simblo\Silex\Exception\NoEnvironmentSetException', 'No valid runtime environment was set.');
        
        putenv('SILEX_ENVIRONMENT=foo');
        
        $app = new Application();
        $app->register(new EnvironmentServiceProvider(array('default' => false)));
    }
}
