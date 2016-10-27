<?php
namespace Nhtm\Dingrong;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/11
 * Time: 10:47
 */

use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Config;

class Dingrong
{
    private $username = '';
    private $password = '';


    public function __construct()
    {
        $this->username = Config::get('dingrong.username');
        $this->password = Config::get('dingrong.password');
    }

    /*
     * 近似查询
     */
    public function getLike($type, $name)
    {
        $url = 'http://www.fsdr.com.cn/user/interface/find?username=' . $this->username . '&password=' . $this->password . '&type=' . $type . '&name=' . $name;
        return $this->dingrongCurl($url);
    }

    /*
     * 根据 申请人 或者 申请号查询
     */
    public function search($num=0,$proposer=''){
        $url='http://www.fsdr.com.cn/user/interface/search?username=' . $this->username . '&password=' . $this->password ;
   
        if($num){
            $url.='&num='.$num;
        }elseif (!empty($proposer)){
            $url.='&proposer='.$proposer;
        }else{
            return false;
        }
        return $this->dingrongCurl($url);
    }
    /*
     * 查看商标详情
     */
    public function getMessage($num,$type){
        $url='http://www.fsdr.com.cn/user/interface/getmessage?username=' . $this->username . '&password=' . $this->password ;

        if($type && $num){
            $url.='&type='.(int)$type.'&num='.$num;
        }else{
            return false;
        }

        return $this->dingrongCurl($url);
    }

    /*
     * 获取图片
     */
    public function getImages($num,$type,$info=1){
        $url='http://www.fsdr.com.cn/user/interface/getpic?username=' . $this->username . '&password=' . $this->password .'&num='.$num.'&type='.(int)$type.'&info='.$info;

        return file_get_contents($url);
    }

    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */

    function dingrongCurl($url, $params = false, $ispost = 0)
    {
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }

}