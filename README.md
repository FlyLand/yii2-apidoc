## 项目描述
    jid是一个类似gii-module的一个拓展模块，主要为了接口端调试和API文档编写
## 安装
在YII2项目路径/components/下git clone http://gitlab.jhongnet.com:8888/zhangke/jid.git ,
或下载项目后直接copy到YII2项目路径/components/下。

## 访问
直接访问项目地址根目录即可。登录默认密码：jiahong

## 项目配置：
将下方配置引入入口文件web/index-dev.php或web/index-test.php( 正式环境不要引入）：
``` php
$config['modules']['jid'] = [
    'class'=>'app\components\jid\Module',
    'name'=>'接口调试系统',
    'password'=>'jh',
    'ipFilters'=>['*','::1'],
    'loginConfig'=>[
        'loginUrl' => '/sail/seller/login',
        'fieldMapping'=>[
            'account'=>'domain',
            'type'   => 'type',
            'password'=>'password',
            'platform'=>'platform',
            'email' => 'seller_email'
        ],
        'c_identity'=>'jidinvoke',
        'passwordHashUrl'=>'',
    ],
    'signUrl'=>'/jid/default/signature',
    'xhprofUrl' => 'http://192.168.1.254:8888/xhprof_html/index.php',
];

$config['defaultRoute']  = 'jid';
if(isset($_REQUEST['xhprof']) && $_REQUEST['xhprof'] == 1){
    xhprof_enable(XHPROF_FLAGS_CPU+XHPROF_FLAGS_MEMORY);
}
```

## Phpstrom 注释配置：
打开phpstrom->setting->Editor->File and Code Templates->Includes，将两个文件内容替换原本的文件内容,点击apply即可

### 配置文件：
PHP Function Doc Comment
```
/**
#if (${NAME.indexOf('action')} != '-1' && ${NAME}!='actions')
@brief 接口名称
@param type $name name defaultValue
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

## 使用jid编写程序注释
1、检索项目第一级目录下的Modules模块下所有Module.php文件，注释参数如下：

    | 注释参数        | 作用    |  备注  |
    | --------   | -----:   | :----: |
    | jid-enable        | 标明此module将被收录到文档中      |       |
    | jid-id        | id值      |       |
    | jid-name        | 模块名称，将显示在页面上方      |       |

2、检索modules具体模块下controllers文件夹下所有controller文件，并遍历所有controller文件中所有action开头的所有方法，方法注释规范如下：

    | 注释参数        | 作用    |  备注  |
    | --------   | -----:   | :----: |
    | brief      | 标明方法名称，将会显示在页面左方      |       |
    | param        | 需要传入的参数，将会动态添加到页面from表单中      | string(类型) $name(参数) 姓名(注释) 张三(默认值)      |
    | method        | 调用方法，POST/GET      |       |
    | return        | 返回参数，将会显示在页面右方      |       |
    | throws        | 异常声明，将会显示在页面右方      |       |
    | detail        | 接口说明，将会显示在页面右方      |       |
