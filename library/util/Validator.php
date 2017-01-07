<?php
namespace Library\Util;

    /**
     * errorCode.php
     *
     * @copyright    http://yanue.net/
     * @author        yanue <yanue@outlook.com>
     * @version        1.0.0 - 2013-07-19
     */

/**
 * Class Validator
 *
 */
class Validator
{

    /**
     * Create a new Validator instance.
     *
     * @param  array $data
     * @param  array $rules
     * @param  array $messages
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * 验证用户名
     * --说明:长度为5-16位字符长度,只能包含字母数字(开始必须字母).
     * @param $username
     * @return array|int
     */
    public static function validUsername($username)
    {
        if (strlen($username) < 5) {
            return false;
        }

        // 不包含中文（最多20个字符）
        if (((mb_strlen($username, 'utf8') == strlen($username)) && strlen($username) > 16)) {
            return false;
        }

        // 包含中文（最多12个中文）
        if (((mb_strlen($username, 'utf8') != strlen($username)) && mb_strlen($username, 'utf8') > 12) || strlen($username) < 5) {
            return false;
        }

        if (!preg_match('/^[a-z\x{4e00}-\x{9fa5}]+[a-zA-Z0-9_\x{4e00}-\x{9fa5}]+$/iu', $username)) {
            return false;
        };

        return true;
    }

    /**
     * 验证用户名
     * --说明:长度为5-16位字符长度,只能包含字母数字(开始必须字母).
     * @param $username
     * @return array|int
     */
    public static function validPassword($passwd)
    {
        if (strlen($passwd) > 16 || strlen($passwd) < 5) {
            return false;
        }
        return preg_match('/^([A-Za-z0-9])+$/i', $passwd);;
    }

    /**
     * Validate that an attribute is numeric.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public static function validateNumeric($value)
    {
        return is_numeric($value);
    }

    /**
     * Validate an attribute is contained within a list of values.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    public static function validateIn($value, $parameters)
    {
        return in_array($value, $parameters);
    }

    /**
     * Validate an attribute is not contained within a list of values.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    public static function validateNotIn($value, $parameters)
    {
        return !in_array($value, $parameters);
    }


    /**
     * Validate that an attribute is a valid IP.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public static function validateIp($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Validate that an attribute is a valid e-mail address.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public static function validEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate that an attribute is a valid URL.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public static function validateUrl($value)
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validate that an attribute is an active URL.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public static function validateActiveUrl($value)
    {
        $url = str_replace(array('http://', 'https://', 'ftp://'), '', strtolower($value));

        return checkdnsrr($url);
    }

    /**
     * Validate the MIME type of a file is an image MIME type.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public static function validateImage($value)
    {
        return self::validateMimes($value, array('jpeg', 'png', 'gif', 'bmp'));
    }

    /**
     * Validate the MIME type of a file test attribute is in a set of MIME types.
     *
     * @param  string $attribute
     * @param  array $value
     * @param  array $parameters
     * @return bool
     */
    public static function validateMimes($value, $parameters)
    {
        if (!$value instanceof \File or $value->getPath() == '') {
            return true;
        }

        // The Symfony File fdfs should do a decent job of guessing the extension
        // based on the true MIME type so we'll just loop through the array of
        // extensions and compare it to the guessed extension of the files.
        return in_array($value->guessExtension(), $parameters);
    }

    /**
     * Validate that an attribute contains only alphabetic characters.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public static function validateAlpha($value)
    {
        return preg_match('/^([a-z])+$/i', $value);
    }

    /**
     * Validate that an attribute contains only alpha-numeric characters.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public static function validateAlphaNum($value)
    {
        return preg_match('/^([a-z0-9])+$/i', $value);
    }

    /**
     * Validate that an attribute contains only alpha-numeric characters, dashes, and underscores.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public static function validateAlphaDash($value)
    {
        return preg_match('/^([a-z0-9_-])+$/i', $value);
    }

    /**
     * Validate that an attribute passes a regular expression check.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    public static function validateRegex($value, $parameters)
    {
        return preg_match($parameters, $value);
    }

    /**
     * Validate that an attribute is a valid date.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public static function validateDate($value)
    {
        if ($value instanceof \DateTime) return true;

        if (strtotime($value) === false) return false;

        $date = date_parse($value);

        return checkdate($date['month'], $date['day'], $date['year']);
    }

    /**
     * Validate that an attribute matches a date format.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    public static function validateDateFormat($value, $parameters)
    {
        $parsed = date_parse_from_format($parameters[0], $value);

        return $parsed['error_count'] === 0;
    }

    /**
     * Validate the length of string/numberic is between $min and $max
     * @param string|numberic $value
     * @param number $min
     * @param number $max
     */
    public static function validateLength($value, $min = 0, $max = null)
    {
        if (empty($min) && empty($max)) {
            return false;
        }

        if (!empty($min) && strlen($value) > $max) {
            return false;
        }

        if (!empty($max) && strlen($value) < $min) {
            return false;
        }

        return true;
    }

    /**
     * Validate the number is between $min and $max
     * @param number $value
     * @param number $min
     * @param number $max
     * @return boolean
     */
    public static function validateNumberRange($value, $min = 0, $max = null)
    {
        if (empty($min) && empty($max)) {
            return false;
        }

        if (empty($min) && ($value) > $max) {
            return false;
        }

        if (empty($max) && ($value) < $min) {
            return false;
        }

        return true;
    }

    public static function validateCellPhone($phone)
    {
//         return self::validateRegex($phone, '/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|189[0-9]{8}$/');
        return self::validateRegex($phone, '/^13[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$/');
    }

    /**
     * 验证昵称
     * @param unknown $nick
     * @return string|boolean|number
     */
    public static function validateNick($nick)
    {
        if (strlen($nick) > 16 || strlen($nick) < 5) {
            return false;
        }
        if (preg_match('/^([a-z]+)([a-z0-9]+)$/i', $nick) == 0) {
            return false;
        };
        return true;
    }

    /**
     * 验证昵称
     * @param unknown $nick
     * @return string|boolean|number
     */
    public static function validateAliasName($name)
    {
        if (strlen($name) > 30 || strlen($name) < 2) {
            return false;
        }
        if (preg_match('/^([a-z]+)([0-9a-zA-Z_-]+)$/i', $name) == 0) {
            return false;
        };
        return true;
    }


}