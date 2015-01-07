<?php
/**
 * This file is part of EnvironmentServiceProvider for Silex
 *
 * @link https://github.com/simblo/environment-service-provider EnvironmentServiceProvider
 * @copyright (c) 2014, Holger Braehne
 * @license http://raw.github.com/simblo/environment-service-provider/master/LICENSE MIT
 */
namespace Simblo\Silex\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Simblo\Silex\Exception\NoEnvironmentSetException;

/**
 * EnvironmentServiceProvider
 *
 * @author Holger Braehne <holger.braehne@simblo.org>
 * @since 1.0.0
 */
class EnvironmentServiceProvider implements ServiceProviderInterface
{
    private $default;
    private $filepath;
    private $filename;
    private $variable;
    private $environments = array();

    /**
     * Constructor.
     */
    public function __construct(array $options = array())
    {
        $this->default  = true === isset($options['default']) ? $options['default'] : 'development';
        $this->filepath = true === isset($options['filepath']) ? $options['filepath'] : false;
        $this->filename = true === isset($options['filename']) ? $options['filename'] : '.setenv';
        $this->variable = true === isset($options['variable']) ? $options['variable'] : 'SILEX_ENVIRONMENT';

        $this->environments = true === isset($options['environments']) ? $options['environments'] : array(
            'development',
            'testing',
            'staging',
            'production'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $environment = $this->determine();

        if (false !== $environment && true === $this->isValid($environment)) {
            $app['environment'] = $environment;
        } elseif (false !== $this->default) {
            $app['environment'] = $this->default;
        } else {
            throw new NoEnvironmentSetException('No valid runtime environment was set.');
        }
    }

    /**
     * Dertermines the runtime environment by checking for a file or an
     * environment variable containing the applications runtime environment
     * name. If both checks fail it will return false.
     *
     * @return string|bool
     */
    private function determine()
    {
        if (false !== $this->filepath && true === is_readable($this->filepath . '/' . $this->filename)) {
            return strtolower(trim(file_get_contents($this->filepath . '/' . $this->filename)));
        } elseif (null !== getenv($this->variable)) {
            return getenv($this->variable);
        } else {
            return false;
        }
    }

    /**
     * Validates the given environment against the internal environments array
     * to ensure the application will run in a known environment.
     *
     * @param string $environment
     * @return bool
     */
    private function isValid($environment)
    {
        return in_array($environment, $this->environments);
    }
}
