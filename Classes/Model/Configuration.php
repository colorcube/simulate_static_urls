<?php
declare(strict_types=1);

namespace Colorcube\SimulateStaticUrls\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Simple object to handle some configuration values
 *
 * @author Rene Fritz (r.fritz@colorcube.de)
 */
class Configuration extends AbstractConfiguration
{

    const ParameterEncodingTypeMd5 = 'md5';
    const ParameterEncodingTypeBase64 = 'base64';

    const FormatLowerCase = 'lowercase';
    const FormatUpperCase = 'uppercase';
    const FormatCamelCase = 'camelcase';

    /**
     * stores the configuration values for url encoding
     * @var array
     */
    protected $_config = [
        'absRefPrefix' => '/',

        'language' => true,
        // language0 = de
        // language1 = en/gb

        'path' => true,
        'pathSegmentMaxLength' => null,
        'pathSegmentSmartTruncate' => false,
        'pathSegmentReplacementChar' => '_',
        'pathSegmentFormat' => self::FormatLowerCase,

        'titleMaxLength' => 40,
        'titleSmartTruncate' => false,
        'titleReplacementChar' => '_',
        'titleFormat' => self::FormatLowerCase,

        // if you want some parameters shorter
        'parameterEncodingType' => self::ParameterEncodingTypeMd5
    ];


    protected $pEncodingAllowedParamNames = null;
    protected $pEncodingExcludedParamNames = null;


    /**
     * Init config values
     *
     * @param array $configArray
     * @return void
     */
    public function __construct(array $configArray)
    {
        // add language config
        // language0 = de
        // language1 = en/gb
        $languages = $configArray['language.'];
        unset($configArray['language.']);
        foreach ((array)$languages as $key => $value) {
            $this->_config['language.'.$key] = (string)$value;
        }

        foreach ($configArray as $key => $value) {
            $this->setValue($key, $value);
        }
    }


    /**
     * Set a config value
     * We cast to the right type and do some logging but no fatal error
     *
     * @param string $configKey Pointer to an "object" in the config array
     * @param mixed $value Value to be set.
     * @return void
     */
    public function setValue($configKey, $value)
    {
        if ($configKey) {
            $this->_config[$configKey] = $value;

            switch ($configKey) {

                case 'absRefPrefix':
                    $this->_config[$configKey] = (string)$value;
                    break;


                case 'language':
                    $this->_config[$configKey] = (boolean)$value;
                    break;

                case 'path':
                    $this->_config[$configKey] = (boolean)$value;
                    break;

                case 'pathSegmentMaxLength':
                    $this->_config[$configKey] = (int)$value;
                    break;

                case 'pathSegmentSmartTruncate';
                    $this->_config[$configKey] = (boolean)$value;
                    break;

                case 'pathSegmentReplacementChar':
                    $this->_config[$configKey] = (string)$value;
                    break;

                case 'pathSegmentFormat':
                    switch ($value) {
                        case self::FormatLowerCase:
                            $this->_config[$configKey] = (string)$value;
                            break;
                        case self::FormatUpperCase:
                            $this->_config[$configKey] = (string)$value;
                            break;
                        case self::FormatCamelCase:
                            $this->_config[$configKey] = (string)$value;
                            break;
                        default:
                            error_log(__METHOD__ . ' unknown format value (not lowercase, uppercase, camelcase): ' . $value);
                    }
                    break;

                case 'titleMaxLength':
                    $this->_config[$configKey] = (int)$value;
                    break;

                case 'titleSmartTruncate':
                    $this->_config[$configKey] = (boolean)$value;
                    break;

                case 'titleReplacementChar':
                    $this->_config[$configKey] = (string)$value;
                    break;

                case 'titleFormat':
                    switch ($value) {
                        case self::FormatLowerCase:
                        case self::FormatUpperCase:
                        case self::FormatCamelCase:
                            $this->_config[$configKey] = (string)$value;
                            break;
                        default:
                            error_log(__METHOD__ . ' unknown format value (not lowercase, uppercase, camelcase): ' . $value);
                    }
                    break;

                case 'parameterEncodingType':
                    switch ($value) {
                        case self::ParameterEncodingTypeMd5:
                        case self::ParameterEncodingTypeBase64:
                            $this->_config[$configKey] = (string)$value;
                            break;
                        default:
                            error_log(__METHOD__ . ' unknown parameter encoding type (not md5, base64): ' . $value);
                    }
                    break;

                case 'parameterEncodingInclude':
                    $this->_config['pEncodingAllowedParamNames'] = GeneralUtility::trimExplode(',', $$value, 1);
                    break;

                case 'parameterEncodingExclude':
                    $this->_config['pEncodingExcludedParamNames'] = GeneralUtility::trimExplode(',', $$value, 1);
                    break;

                default:
                    error_log(__METHOD__ . ' unknown configuration option: ' . $configKey);
            }
        }
    }

}


