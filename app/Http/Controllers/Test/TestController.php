<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHPUnit\Util\RegularExpression;
use GuzzleHttp\Client;
class TestController extends Controller
{
    /**
     * curl get 方式访问
     */
   public function curl1()
   {
//       echo 11111;die;
       //访问百度
       $url = "https://www.baidu.com";

       //1 初始化
       $ch = curl_init($url);

       //2 设置参数
       curl_setopt($ch,CURLOPT_RETURNTRANSFER,0);

       //3 执行会话
       curl_exec($ch);

       //关闭会话
       curl_close($ch);

   }

   /*
    * curl post 访问方式
    */
    public function curl3()
    {
        echo '<pre>';print_r($_POST);echo '</pre>';
    }

    /**
     * 获取微信 access_token 方法
     */
    public function curl2()
    {
      $appid = "wxb0bbadfc8910f9e8";
      $appsecret = "23431fe90f4bceba6d0656b4376e63c2";

      //1 初始化
      $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
      $ch = curl_init($url);

      //2 设置参数
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

      //3 执行会话
      $data = curl_exec($ch);

      //4 关闭会话
      curl_close($ch);

      //5 处理数据
      return $data;
      
    }

    /**
     * 创建自定义菜单
     */
    
    public function menu()
    {
        $data = $this->curl2();
        // dd($data);
        $data = json_decode($data,true);
        // dd($data);
        $access_token = $data['access_token'];
        // dd($access_token);
        // 自定义菜单数据
        $post_data = '{
         "button":[
         {    
              "type":"click",
              "name":"今日歌曲",
              "key":"V1001_TODAY_MUSIC"
          }]
       }';
       //1 初始化 
       $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
       $ch = curl_init($url);

       //2 设置参数
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

       //3 执行会话
       $data = curl_exec($ch);

       //4 关闭会话
       curl_close($ch);
        
      //查看数据
      dd($data);
    }

  /**
   *文件上传
   */
  public function upload()
  {
    return view('upload/upload');
  }

  public function up(Request $request,$name)
  {
    if ($request->file($name)->isValid()) {
            $photo = $request->file($name);
//            $extension = $photo->extension(); //获取后缀
            $Extension=$photo->getClientOriginalExtension();  //获取未处理的上传文件后缀
            $store_result = $photo->storeAs(date('Ymd'), date('Ymd') . rand(100, 999) . '.' . $Extension);
            return ($store_result);
        }
        exit('未获取到上传文件或上传过程出错');
  }

  /**
   * curlpost 调用接口
   */
  

  public function updo()
  {

    echo __METHOD__;

    $post_data = [
        'zhang' => 12312312,
       'name'=> new \CURLFile('uploads/20190612/20190612890.jpg')
    ];
    // dd($post_data);
     // echo 1111;die;
    //1 初始化
    $url = "http://wangxin.1810lum.com/upload";
    $ch = curl_init($url);
    // dd($ch);
    //2 设置参数
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    // //3 执行会话
    $cont = curl_exec($ch);
    //echo $cont;

    //echo $cont;die;
   
    // //4 关闭会话
    curl_close($ch);
  }

  /**
   * 数据加密测试
   */
  public function ceshi()
  {
      $data = "wangxin";
      $enc_data = base64_encode($data);
      echo $enc_data;

       $c = new Client();
      $url = "http://wangxin.1810lum.com/user/ceshi";

      $r = $c ->request('POST',$url,[
            'body' =>$enc_data
      ]);
      echo "<hr>";
      echo $r->getBody();
  }

  /**
   * 数据加密 对称加密
   */
  public function encypt1()
  {
      $data = 'wangxin';     //初识数据
      $method = "AES-128-CBC";  //加密算法
      $key = "password";        //加密秘钥
      $iv = "qwertyuiopasdfgh"; //初始化向量  16字节

      //对称加密
      $enc_data = openssl_encrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);  //加密数据
      var_dump($enc_data);


      $c = new Client();
      $url = "wangxin.1810lum.com/user/decypt1";    //访问路径
      $r = $c ->request('POST',$url,[
          'body' =>base64_encode($enc_data)
      ]);

      echo "<hr>";
      echo $r ->getBody();


  }

  /**
   * 数据加密 非对称数据加密  私钥加密
   */
  public function encypt2()
  {
      $data = "王鑫";
      $private_key = openssl_get_privatekey("file://".public_path("keys/priv.pem"));
      openssl_private_encrypt($data,$enc_data,$private_key);
//      var_dump($enc_data);
      echo "加密数据：".$enc_data;
//      dd($enc_data);

      $c = new Client();
      $url = "wangxin.1810lum.com/user/decypt2";    //访问路径
      $r = $c ->request('POST',$url,[
          'body' =>$enc_data
      ]);

      echo "<hr>";
      echo $r ->getBody();

  }


  /**
   * 6.13 练习
   */
   public function lianxi()
   {
       $data = '数据加密练习';
       $method = "AES-128-CBC";  //加密算法
       $key = "password";        //加密秘钥
       $iv = "asdfghjklzxcvbnm"; //初始化向量  16字节

       echo "初始数据：".$data;echo "<hr>";

       //对称加密
       $enc_data = openssl_encrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);  //加密数据
       echo "首次加密数据：".$enc_data;echo"<hr>";

       //私钥生成签名
       $private_key = openssl_get_privatekey("file://".public_path("keys/priv.pem"));
       openssl_sign($enc_data,$signature,$private_key);
       echo "首次私钥生成签名:".$signature;echo"<hr>";
       $datainfo = [
           'enc_datd'=>$enc_data,
           'signature'=>$signature
       ];
       $dataInfo = serialize($datainfo);

       //发送数据
       $c = new Client();
       $url = "http://wangxin.1810lum.com/user/lianxi";    //访问路径
       $r = $c ->request('POST',$url,['body'=>$dataInfo]);

//       echo "<hr>";
       echo $r ->getBody();

   }


   /**
    * 6.13 练习2
    */

   public function lianxi2()
   {
       $data=file_get_contents('php://input');
       $data=unserialize($data);
//        dd($data);
       $enc_data = $data['enc_datd'];
       $str1 = $data['signature'];

       //获取公钥
       $pub_key = openssl_get_publickey("file://".public_path("keys/pub2.key"));
       //验证签名
       $ok = openssl_verify($enc_data, $str1, $pub_key);

//       dd($ok);
       if($ok == 1){
           echo "再次验签成功";echo "<hr>";

           $method = "AES-128-CBC";  //加密算法
           $key = "password";        //加密秘钥
           $iv = "zzzzzzzzzzzzzzzz"; //初始化向量  16字节

           $dec_data = openssl_decrypt($enc_data,$method,$key,OPENSSL_RAW_DATA,$iv);  //解密数据
           echo "再次解密数据：".$dec_data;echo"<hr>";

       }
   }
}
  