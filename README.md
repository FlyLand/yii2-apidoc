## 项目描述
    1、规范API接口的注释。注释即文档，注释结构不对无法渲染出页面，更无法与对接方交流

    2、节省前后端文档定义和书写。文档按照基本格式输出，该有的元素都存在，极大的减少前后端交流成本。

    3、文档注释方便。配合phpstrom的自定制注释输出，不需要花费额外时间背文档特殊定义词汇。
## 安装
composer require landrain/yii2-apidoc

## 访问
直接访问项目地址根目录即可。登录默认密码：123456

## 准备
1、请确保yii2开启了url美化功能，
``` php
'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
    ],
],
```
2、本项目是以module模块的接口开发为基础，请确定项目下有modules模块
3、本项目暂时仅用于yii2-basic项目，暂不适用于yii2高级版

## 项目配置：
将下方配置引入入口文件web/index-dev.php或web/index-test.php( 正式环境不要引入）：
``` php
$config['modules']['jid'] = [
    'class'=>'landrain\apidoc\Module', //v1.0.2版本前为 landrain\Module
    'name'=>'接口调试系统',
    'password'=>'123456',
    'ipFilters'=>['*','::1'],
    'language' => 'zh',
    'loginConfig'=>[
        'loginUrl' => '/sail/seller/login',
        'fieldMapping'=>[
            'account'=>'domain',
            'password'=>'password',
        ],
    ],
    'subOfClasses' => [], //需要继承的classes
    'dropdownList' => [
    "android下载" => "https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1547463466873&di=3e5a65b07a4dddf84fce5f421f0b64ca&imgtype=0&src=http%3A%2F%2Fy3.ifengimg.com%2Fnews_spider%2Fdci_2013%2F09%2Fb85234c4801f8b2d7771353867a7a0f8.jpg"
    ], //右上角下拉image
    'xhprofUrl' => 'http://192.168.1.254:8888/xhprof_html/index.php', //xhprofUrl链接
];

$config['defaultRoute']  = 'jid';
if(isset($_REQUEST['xhprof']) && $_REQUEST['xhprof'] == 1){
    xhprof_enable(XHPROF_FLAGS_CPU+XHPROF_FLAGS_MEMORY);
}
```

### ['modules']['jid']配置说明：

  注释参数  | 作用 | 备注
  ------------- | ------------- |  -------------
  class  | 模块入口 |
  name  | 模块名称，将会显示在页面 |
  password | 登录模块的密码 |
  ipFilters | IP登录限制 |
  language | 语言选择 | 简单支持en/zh
  loginUrl | 登录地址 | 项目中的登录地址
  fieldMapping | 登录参数名和显示名称 |
  subOfClasses | 需要继承的指定class,若为空则将所有controller囊括，否则必须继承这些类才会显示在页面上
  dropdownList | 显示在右上角的图片，一般用作下载二维码 | 格式为：显示名称:图片地址
  xhprofUrl | xhprof的配置地址 |


## Phpstrom 注释配置：
打开phpstrom->setting->Editor->File and Code Templates->Includes，将两个文件内容替换原本的文件内容,点击apply即可

### 配置文件：
PHP Function Doc Comment
```
/**
#if (${NAME.indexOf('action')} != '-1' && ${NAME}!='actions')
@brief 接口名称
@param type $name this is your test name (=name:defaultValue=)
@method POST/GET
@detail 接口描述
@return array
@throws Null
#end
${PARAM_DOC}
#if (${TYPE_HINT} != "void") * @return ${TYPE_HINT}
#end
${THROWS_DOC}
*/
```

PHP Class Doc Comment
```
/**
 * Class ${NAME}
#if (${NAMESPACE}) * @package ${NAMESPACE}
#end
#if ('Module' == ${NAME})
@jid-enable
@jid-name 模块名称
@jid-id 模块ID
#elseif (${NAME.indexOf('Controller')}!=-1)
@brief controller名称
#end
 */
```

## 检查phpstorm配置
在SiteController或任意controller文件上，输入/** 后输入enter键，将会输出注释
[演示图片](https://jsinchina.oss-cn-qingdao.aliyuncs.com/flash/index.gif)

## 使用jid编写程序注释
1、检索项目第一级目录下的Modules模块下所有Module.php文件，注释参数如下：

  注释参数  | 作用 | 备注
  ------------- | ------------- |  -------------
 jid-enable  | 标明此module将被收录到文档中 |
 jid-id  | id值 |
 jid-name | 模块名称，将显示在页面上方 |


2、检索modules具体模块下controllers文件夹下所有controller文件，并遍历所有controller文件中所有action开头的所有方法，方法注释规范如下：

  注释参数  | 作用 | 备注
  ------------- | ------------- |  -------------
 brief         | 标明方法名称，将会显示在页面左方      |
 param        | 需要传入的参数，将会动态添加到页面from表单中      | string(类型) $name(参数) 这是一个人的姓名(注释) (=姓名:张三=)
 method        | 调用方法，POST/GET      |
 return        | 返回参数，将会显示在页面右方      |
 throws        | 异常声明，将会显示在页面右方      |
 detail        | 接口说明，将会显示在页面右方      |



## 示例：
先配置好phpstome注释配置。
1、配置modules模块注释
2、配置controller类的注释
3、配置action的注释

[测试项目源码地址](https://github.com/FlyLand/yii2-apidoc-basic-test)
[测试项目访问地址](http://api.storecardhome.cn) 默认密码：123456