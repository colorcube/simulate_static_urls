<?php
declare(strict_types=1);
namespace Colorcube\SimulateStaticUrls\Utility;

/**
 * An abstract utility class to handle string manipulation.
 * All methods are statically available.
 *
 * @author Rene Fritz
 */
abstract class StringUtility {


    /**
     * Truncates the string to a given length but tries to cut at wound boundaries.
     *
     * @param string $strString string to truncate
     * @param integer $intMaxLength the maximum possible length of the string to return.
     * @return string the full string or the truncated string with ellipses
     */
    public static function truncateSmart(string $strText, int $intMaxLength) :string
    {
        if (extension_loaded('mbstring')) {
            $funcStrLen = 'mb_strlen';
            $funcSubStr = 'mb_substr';
            $funcStrRPos = 'mb_strrpos';
        } else {
            $funcStrLen = 'strlen';
            $funcSubStr = 'substr';
            $funcStrRPos = 'strrpos';
        }

        if ($funcStrLen($strText) > $intMaxLength) {
            $strText = $funcSubStr($strText, 0, $intMaxLength);

            $pos = $funcStrRPos($strText, ' ');
            if ($pos < (int)($funcStrLen($strText) * 0.6)) {
                $strText = $funcSubStr($strText, 0, $pos);
            }
        }

        return $strText;
    }

    /**
     * Converts input string to an ASCII based
     *
     * @param string $inTitle String to base output on
     * @param string $whitespaceChar String to be used to replace whitespaces
     * @param integer $maxTitleChars Maximum number of characters in the string
     * @param bool $smartTruncate Truncates the string at wound boundaries if possible
     * @param string $format lowercase, uppercase, camelcase
     * @return string Converted string
     */
    public static function convertStringToAscii(string $inTitle, string $whitespaceChar, int $maxTitleChars, bool $smartTruncate, string $format) :string
    {
        $out = Unidecode::Decode($inTitle);

        switch ($format) {
            case 'lowercase':
                $out = strtolower($out);
                break;
            case 'uppercase':
                $out = strtoupper($out);
                break;
            case 'camelcase':
                $out = ucwords($out);
                break;
            default:
                if ($format) {
                    error_log('Wrong format (valid: lowercase, uppercase, camelcase) set in simulateStaticUrls configuration:' . $format);
                }
        }

        $out = preg_replace('/[^A-Za-z0-9_-]/', $whitespaceChar, trim($out));
        if ($whitespaceChar) {
            $out = preg_replace('/([' . $whitespaceChar . ']){2,}/', '\1', $out);
            $out = preg_replace('/[' . $whitespaceChar . ']?$/', '', $out);
            $out = preg_replace('/^[' . $whitespaceChar . ']?/', '', $out);
        }

        if ($maxTitleChars) {
            if ($smartTruncate) {
                $out = self::truncateSmart($out, $maxTitleChars);
            } else {
                $out = substr($out, 0, $maxTitleChars);
            }
        }

        return $out;
    }

}