<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    private $pri_key = "Media";
    /**
     * 密钥文件的路径
     */
    protected $privateKeyFilePath = 'rsa_private_key.pem';
    /**
     * 公钥文件的路径
     */
    protected $publicKeyFilePath = 'rsa_public_key.pem';

    //生成随机字符串token

    public function getToken($uid,$secret){
        $key = $this -> pri_key;
        $rand_char = $this -> generate_rand_char();
        //$re_secret = md5(md5($secret.substr($secret,0,5))."media");
        $time = time();
        $str = $this -> pri_key . $uid . time()  . $rand_char;

        $param = [
            'rand_char' => $rand_char,
            'uid' => $uid,
            'time' => $time,
            'pri_key' => $key
        ];
        sort($param, SORT_STRING);
        $strParam = implode($param);

        //todo 存储到redis，
        return sha1($strParam);
    }

    /**
     * 生成随机字符串
     */

    function generate_rand_char($length = 8)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str   = '';
        for ($i = 0; $i < $length; $i++) {

            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }

    /**
     * 用私钥加密
     * @param $originalData  需要加密的数据
     * @return $encryptData  加密后的数据
     */
    protected function encrypt($originalData)
    {
        $encryptData = '';
        $privateKey = openssl_pkey_get_private(file_get_contents($this->privateKeyFilePath));
        if (openssl_private_encrypt($originalData, $encryptData, $privateKey)) {

            /**
             * 加密后 可以base64_encode后方便在网址中传输或者打印  否则打印为乱码
             */
            $result = base64_encode($encryptData);
            echo '加密成功，加密后数据(base64_encode后)为:', $result, PHP_EOL;
            return $result;
        } else {
            die('加密失败');
        }

    }

    /**
     * 用公钥解密
     * @param $encryptData  需要解密的数据
     * @return $decryptData 解密后的数据
     */
    protected function decrypt($encryptData)
    {
        $decryptData = '';
        $publicKey = openssl_pkey_get_public(file_get_contents($this->publicKeyFilePath));
        if (openssl_public_decrypt(base64_decode($encryptData), $decryptData, $publicKey)) {

            //echo '解密成功，解密后数据为:', $decryptData, PHP_EOL;
            return $decryptData;
        } else {
            die('解密成功');
        }
    }

}



