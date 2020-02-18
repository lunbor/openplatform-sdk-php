<?php
/**
 * Created by PhpStorm.
 * User: lf
 * Date: 2018/9/6
 * Time: 上午11:40
 */

namespace Gd;

class Sdk
{

    private $env;

    private $appkey;

    private $appsecret;

    private $ver;

    public $baseUrl = [
        "test" => "https://openapi.yewifi.com",
        "prod" => "https://openapi.wetax.com.cn"
    ];

    public $log = [];


    /**
     * Sdk constructor.
     * @param $appkey
     * @param $appsecret
     * @param string $env 环境，只能为test或者prod
     * @throws \Exception
     */
    public function __construct($appkey, $appsecret, $env='test', $ver = "1.0.0")
    {
        if ( ! extension_loaded("curl") ){
            throw new \Exception("golden sdk need curl");
        }
        $this->setEnv($env);
        $this->setAppkey($appkey);
        $this->setAppsecret($appsecret);
        $this->setVer($ver);
    }

    /**
     * @param $env
     * @throws \Exception
     */
    public function setEnv($env)
    {
        if( !in_array($env, ['test', 'prod']) ) throw new \Exception("env must be test or prod");
        $this->env = $env;
    }

    /**
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @return string
     */
    public function getAppkey()
    {
        return $this->appkey;
    }

    /**
     * @return string
     */
    public function getAppsecret()
    {
        return $this->appsecret;
    }

    /**
     * @param $appkey
     */
    public function setAppkey($appkey)
    {
        $this->appkey = $appkey;
    }

    /**
     * @param $appsecret
     */
    public function setAppsecret($appsecret)
    {
        $this->appsecret = $appsecret;
    }

    /**
     * @return mixed
     */
    public function getVer()
    {
        return $this->ver;
    }

    /**
     * @param mixed $ver
     */
    public function setVer($ver)
    {
        $this->ver = $ver;
    }

    /**
     * @return array
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param array $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param $timestamp
     * @param array $data
     * @return string
     */
    public function sign($timestamp, array $data)
    {
        $originStr = $this->getAppkey() . $timestamp;
        ksort($data);
        $encodeStr = rawurlencode(call_user_func(function () use ($data) {
                $str = "";
                foreach ($data as $k => $v) {
                    if (is_array($v)) {
                        $v = json_encode($v, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                    }
                    $str .= $k . '=' . $v . '&';
                }
                $str = rtrim($str, '&');
                return $str;
            })
        );
        $originStr .= $encodeStr . $this->getAppsecret();
        $sign = strtoupper(md5($originStr));
        return $sign;
    }

    private function setLog($log)
    {
        if( $this->getEnv() == 'test' ) {
            $this->log[] = $log;
        }
    }

    public function getLog()
    {
        return $this->log;
    }

    public function httpRequest($url, array $data)
    {

        $baseUri = $this->baseUrl[$this->getEnv()];
        $timestamp = time();
        $sign = $this->sign($timestamp, $data);
        $url .= "?ver=" .$this->ver . "&signature=" . $sign . "&appkey=" . $this->getAppkey() . "&timestamp=" . $timestamp;
        $requestUri = $baseUri . $url;
        $this->setLog("高灯请求地址:" . $requestUri);
        $this->setLog("高灯请求参数:" . print_r($data, true));
        $this->setLog("高灯签名值:" . $sign);
        $result = Util::httpPost($requestUri, $data, 'json');
        if( $result !== false ) $result = json_decode($result, true);
        return $result;
    }

}