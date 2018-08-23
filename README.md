# Remote Login

## 描述
影子数据用于项目远程登录的laravel扩展包

## 安装过程
1.项目根目录引用安装
`composer require alicext/remote-login-for-laravel`

2.在/config/app.php的providers数组增加以下内容
`AliceXT\Remotelogin\RemoteloginServiceProvider::class`

3. 在/config/app.php的aliases数组增加以下内容
> 'Remotelogin' => AliceXT\Remotelogin\Facades\Remotelogin::class 

4.刷新autoload
composer dump-autoload

5.发布资源文件
php artisan vendor:publish --provider="AliceXT\Remotelogin\RemoteloginServiceProvider" 

## 使用方法
### 登录auth
引用
use Remotelogin;
调用登录
$ret = Remotelogin::auth($account, $password);
$ret==false时，使用以下语句获得报错信息
Remotelogin::getError();
否则$ret为登录成功后返回的信息

## 注意事项
由于国内镜像没有该项目的镜像，如果想要使用composer安装项目，建议使用原始镜像，运行以下语句即可使用原始镜像
composer config repo.packagist composer https://packagist.org

另外，中国镜像是以下语句，用于恢复国内镜像
composer config repo.packagist composer https://packagist.phpcomposer.com  
