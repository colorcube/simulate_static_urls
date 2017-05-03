<?php
declare(strict_types=1);

namespace Colorcube\SimulateStaticUrls\Model;

use TYPO3\CMS\Core\Error\Exception;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;


/**
 * Simple object to handle some configuration values
 *
 * @author Rene Fritz (r.fritz@colorcube.de)
 */
abstract class AbstractConfiguration
{

    /**
     * stores the configuration values
     * @var array
     */
    protected $_config = [];


    /**
     * Init config values
     *
     * @param array $configArray
     * @return void
     */
    public function __construct(array $configArray)
    {
        $this->_config = $configArray;
    }

    /**
     * Returns all configuration data as (multidimensional) array
     *
     * @return array
     */
    public function getAll()
    {
        return $this->_config;
    }

    /**
     * Set a config value
     *
     * @param string $configKey Pointer to an "object" in the config array
     * @param    mixed $value Value to be set.
     * @return void
     */
    public function setValue($configKey, $value)
    {
        if ($configKey) {
            $this->_config[$configKey] = $value;
        }
    }

    /**
     * Returns configuration value
     *
     * @param string $configKey Pointer to an "object" in the config array
     * @param mixed $defaultValue Default value will be returned if value is empty
     * @return    mixed    Just the value
     */
    public function getValue($dataKey, $defaultValue = null)
    {
        $value = null;

        if ($dataKey) {
            if (array_key_exists($dataKey, $this->_config)) {
                $value = $this->_config[$dataKey];
            } else {
                throw new Exception(__METHOD__ . ' unknown config option: ' . $dataKey);
            }
        }

        return is_null($value) ? $defaultValue : $value;
    }

    /**
     * Check a config value if its enabled
     * Anything except '' and 0 is true
     * If the option is not set the default value will be returned
     *
     * @param string $configKey Pointer to an "object" in the config array
     * @param mixed $defaultValue Default value will be returned if the option is not
     * @return boolean
     */
    public function isEnabled($configKey, $defaultValue = null)
    {
        $value = $this->getValue($configKey, $defaultValue);
        return is_null($value) ? false : $this->_isEnabled($value);
    }

    /**
     * Checks if an config option exists
     *
     * @param string $configKey Pointer to an "object" in the config array
     * @return   boolean
     */
    public function has($dataKey)
    {
        return array_key_exists($dataKey, $this->_config);
    }

    /**
     * Check a config value if its enabled
     * Anything except '' and 0 is true
     *
     * @param    mixed $value Value to be checked
     * @return bool    Return false if value is empty or 0, otherwise the value itself as boolean
     */
    protected function _isEnabled($value)
    {
        if (!strcmp((string)$value, (string)(int)($value))) { // is integer?
            return (bool)($value);
        }
        return empty($value) ? false : (bool)$value;
    }
}







