<?php
namespace Library\Util;
use Library\Core\Config;
/**
 * Crypt PHP
 *
 * Provides cryptography functionality, including hashing and symmetric-key encryption
 *
 * @package    Crypt
 * @author            Osman Üngür <osmanungur@gmail.com>
 * @copyright  2010-2011 Osman Üngür
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version    Version @package_version@
 * @since      Class available since Version 1.0.0
 * @link       http://github.com/osmanungur/crypt-php
 */

class Encrypt {

    private $data;
    private $key;
    private $ajaxKey;
    private $mode;
    private $cipher;
    private $isAjax = false;
    
    /**
     * @var Encrypt
     */
    private static $instance = null;
    
    /**
     * @var source
     */
    private static $module = null;
    
    const MCRYPT_CIPHER = MCRYPT_RIJNDAEL_128;
    const MCRYPT_MOD = MCRYPT_MODE_ECB;
    const MINIMUM_LENGTH = 16;

    private function __construct() {
        $this->checkEnvironment();
        $key = Config::getSite('encrypt', 'encrypt.secret');
        $this->key = $key;
        $ajaxKey = Config::getSite('encrypt', 'encrypt.ajax.secret');
        $this->ajaxKey = $ajaxKey;
        $this->mode = self::MCRYPT_MOD;
        $this->cipher = self::MCRYPT_CIPHER;
        if(empty(self::$module)) {
            $iv_size = mcrypt_get_iv_size(self::MCRYPT_CIPHER, self::MCRYPT_MOD);
            self::$module = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        }
    }
    
    public static function instance() {
        if(!self::$instance instanceof Encrypt) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    /**
     * Checks the environment for mcrypt and mcrypt module
     *
     * @return void
     * @author Osman Üngür
     */
    private function checkEnvironment() {
        if ((!extension_loaded('mcrypt')) || (!function_exists('mcrypt_module_open'))) {
            throw new \Exception('The PHP mcrypt extension must be installed for encryption', 1);
        }
        if (!in_array(self::MCRYPT_CIPHER, mcrypt_list_algorithms())) {
            throw new \Exception("The cipher used self::MCRYPT_MODULE does not appear to be supported by the installed version of libmcrypt", 1);
        }
    }
    
    public function getModule() {
        return self::$module;
    }

    /**
     * Sets the data for encryption or decryption
     *
     * @param mixed $data
     * @return void
     * @author Osman Üngür
     * @return Encrypt
     */
    public function setData($data) {
        $this->my_encoding($data, 'UTF-8');
        $this->data = $data;
        return $this;
    }
    
    /**
     * 自动检测字符串编码并转换为指定编码
     * @param string $data
     * @param string $to
     * @return string
     */
    private function my_encoding($data, $to)
    {
        $encode_arr = array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS','eucjp-win','sjis-win','EUC-JP');
        $encoded = mb_detect_encoding($data, $encode_arr);
        $data = mb_convert_encoding($data,$to,$encoded);
        return $data;
    }

    /**
     * Sets the secret key for encryption or decryption, at least 8 character long
     *
     * @param string $key
     * @return void
     * @author Osman Üngür
     */
    public function setKey($key, $isAjax = false) {
        if (strlen($key) != self::MINIMUM_LENGTH) {
            $message = sprintf('The secret key must be a minimum %s character long', self::MINIMUM_LENGTH);
            throw new \Exception($message, 1);
        }
        if($isAjax) {
            $this->ajaxKey = $key;
        }
        else {
            $this->key = $key;
        }
        return $this;
    }

    /**
     * Returns the encrypted or decrypted data
     *
     * @return mixed
     * @author Osman Üngür
     */
    private function getData() {
        return $this->data;
    }

    /**
     * Returns the secret key for encryption
     *
     * @return string
     * @author Osman Üngür
     */
    private function getKey() {
        if($this->isAjax()) {
            return $this->ajaxKey;
        }
        return $this->key;
    }

    /**
     * Encrypts the given data using symmetric-key encryption
     *
     * @return string
     * @author Osman Üngür
     */
    public function encode() {
        $block = mcrypt_get_block_size(self::MCRYPT_CIPHER, self::MCRYPT_MOD);
        $str = $this->getData();
        $pad = $block - (strlen($str) % $block);
        $str .= str_repeat(chr($pad), $pad);
        $ciphertext = mcrypt_encrypt(self::MCRYPT_CIPHER, $this->getKey(), $str, self::MCRYPT_MOD);
        return $this->stripUrlChars(base64_encode($ciphertext));
    }

    /**
     * Decrypts encrypted cipher using symmetric-key encryption
     *
     * @return mixed
     * @author Osman Üngür
     */
    public function decode() {
        $data = base64_decode(trim($this->destripUrlChars($this->getData())));
        $ciphertext = mcrypt_decrypt(self::MCRYPT_CIPHER, $this->getKey(), $data, self::MCRYPT_MOD);
        $block = mcrypt_get_block_size(self::MCRYPT_CIPHER, self::MCRYPT_MOD);
        $pad = ord($ciphertext[($len = strlen($ciphertext)) - 1]);
        $len = strlen($ciphertext);
        $pad = ord($ciphertext[$len-1]);
        return substr($ciphertext, 0, strlen($ciphertext) - $pad);
    }
    
    /**
     * 将字符串里的+ / = 替换成- . *
     */
    public function stripUrlChars($str) {
        return str_replace(array('+', '/', '='), array('-', '.', '*'), $str);
    }
    
    public function destripUrlChars($str) {
        return str_replace(array('-', '.', '*'), array('+', '/', '='), $str);
    }
    
    public function isAjax() {
        $this->isAjax = ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
        return $this->isAjax;
    }

    /**
     * hash encrypt
     *
     * @param string $data
     * @return string
     */
    public static function hashCode($data){
        $context = hash_init('sha512', HASH_HMAC, '!@:\"#$%^&*<>?{}$^$@*^&*I@!');
        hash_update($context, $data);

        return md5(hash_final($context));
    }

}

?>