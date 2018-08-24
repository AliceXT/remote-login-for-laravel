<?php
namespace AliceXT\Remotelogin;
use Illuminate\Session\SessionManager;
use Illuminate\Config\Repository;
class remotelogin
{
    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var Remote Url
     */
    protected $remote_url;

    private $errMsg;

    /**
     * Packagetest constructor.
     * @param SessionManager $session
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
        $url_begin = $this->config->get('remotelogin.https') ? 'https://': 'http://';
        $this->remote_url = $url_begin.$this->config->get('remotelogin.remote_url');
    }
    /**
     * @param string $msg
     * @return string
     */
    public function test_rtn($msg = ''){
        $config_arr = $this->config->get('remotelogin.options');
        return $msg.' <strong>from your custom develop package!</strong>>';
    }

    public function changePass($oldpass, $newpass)
    {
        $changePass = $this->config->get('remotelogin.change_password_path');
        $url = $this->remote_url . $changePass;
        $params['oldpass'] = $oldpass;
        $params['newpass'] = $newpass;

        $result = self::curl($url, $params, true);

        if(false === $result){
            return false;
        }
        $json = json_decode($result);

        // echo $result;
        if($json->status_code === 200){
            return true;
        }else{
            // $this->errMsg = 'REMOTE API ERROR!';
            $this->errMsg = $json->message;
            \Log::error('Remotelogin:'.$json->message);
            return false;
        }
        return fasle;
    }


    /**
     * remote login
     * @param  string $user_name user_name for account
     * @param  string $password  password string in plaint text
     * @return mixed|false            remote return or error for false
     */
    public function auth($user_name, $password)
    {
        $auth = $this->config->get('remotelogin.auth_path');
        $url = $this->remote_url . $auth;
        $params['user_name'] = $user_name;
        $params['password'] = $password;

        $result = self::curl($url, $params, true);

        if(false === $result){
            return false;
        }
        $json = json_decode($result);

        // echo $result;
        if($json->status_code === 200){
            if(empty($json->data->uuid)){
                $this->errMsg = "UUID NOT EXIST!";
                return false;
            }
            return $json->data;
        }else{
            // $this->errMsg = 'REMOTE API ERROR!';
            $this->errMsg = $json->message;
            \Log::error('Remotelogin:'.$json->message);
            return false;
        }
        return fasle;
    }

    public function getError()
    {
        return $this->errMsg;
    }


    /**
     * @param $url 请求网址
     * @param bool $params 请求参数
     * @param int $ispost 请求方式
     * @param int $https https协议
     * @return bool|mixed
     */
    public function curl($url, $params = false, $ispost = 0, $https = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);

        if ($response === FALSE) {
            $this->errMsg = "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }
}