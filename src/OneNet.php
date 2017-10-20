<?php
/**
 * Created by PhpStorm.
 * User: bone
 * Date: 2017/10/17
 * Time: 16:37
 */
namespace Boneq\OneNet;

class OneNet
{
    // token
    static $token =null;
    // encodekey
    static $encodekey = null;
    // request input
    static $input;
    // cache
    static $cache;

    /**
     * Verify Token and EncodingAESKey
     */
    protected static function _checkSignature()
    {
        $new_sig = md5(self::$token . self::$input['nonce'] . self::$input['msg']);
        $new_sig = rtrim(str_replace('+', ' ', base64_encode(pack('H*', strtoupper($new_sig)))),'=');
        if ($new_sig == rtrim(self::$input['signature'],'=')) {
            return self::$input['msg'];
        } else {
            return FALSE;
        }
    }

    /**
     * decode json
     * @return bool|\Illuminate\Support\Collection
     */
    protected static function _handleRuleMsg()
    {
        $new_sig = md5(self::$token . self::$input['nonce'] . json_encode(self::$input['msg']));
        $new_sig = rtrim(base64_encode(pack('H*', strtoupper($new_sig))),'=');
        if ($new_sig == rtrim(self::$input['msg_signature'],'=')) {
            return collect(self::$input['msg']);
        } else {
            return FALSE;
        }
    }

    /**
     * Secret decode
     * @return \Illuminate\Support\Collection
     */
    protected static function _decryptMsg()
    {
        $enc_msg = base64_decode(self::$input['enc_msg']);
        $aes_key = base64_decode(self::$encodekey . '=');
        $secure_key = substr($aes_key, 0, 32);
        $iv = substr($aes_key, 0, 16);
        $msg = openssl_decrypt($enc_msg, 'AES-256-CBC', $secure_key, OPENSSL_RAW_DATA, $iv);
        $pattern = '/.*(\{.*\})/';
        $msg = preg_replace($pattern, '${1}', $msg);
        return collect(json_decode($msg));
    }

    /**
     * accept data
     * @param null $callback
     * @return bool|null
     */
    public function server($callback=null)
    {
        $key=array_keys(self::$input);
        if (self::$cache->get('onenet')==self::$input) {
            return null;
        }else{
            self::$cache->put('onenet',self::$input,1);
        }
        sort($key);
        $keys=implode($key);
        $back=null;
        switch ($keys){
            case 'msgmsg_signaturenonce':
                $back=self::_handleRuleMsg();
                break;
            case 'enc_msgmsg_signaturenonce':
                $back=self::_decryptMsg();
                break;
            case 'msgnoncesignature':
                return self::_checkSignature();
                break;
            default:
                break;
        }
        if ($back) {
            call_user_func($callback,$back);
        }
        return null;
    }

    /**
     * OneNet constructor.
     * @param $config app entire instance
     */
    public function __construct($config)
    {
        if (self::$token==null) {
            self::$token=$config['config']['onenet.token'];
            self::$encodekey=$config['config']['onenet.encodekey'];
        }
        self::$input=$config['request']->all();
        self::$cache=$config['cache'];
    }
}