<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/16
 * Time: 11:45
 */

namespace landrain\models;


class Language
{
    public static $lang = 'zh';

    public static function t($name){
        return self::mapping()[self::$lang][$name];
    }

    public static function mapping(){
        return [
            'zh' => [
                'loginButton' => '登录',
                'loginCancelButton' => '取消',
                'loginModal' => '用户登录',
                'logoutBtn' => '退出登录',
                'navbar' => '切换导航',
                'panelTitle' => '接口调试',
                'interfaceError' => '未选择接口或获取参数失败',
                'invokeButton' => '调用接口',
                'invokeXhprofBtn' => 'Xhprof分析',
                'resultMessage' => '输出结果',
                'noResultMessage' => '未调用或调用失败',
                'interfaceExplain' => '接口说明',
                'interfaceUri' => '调用地址',
                'interfaceExplainMethod' => 'HTTP方法',
                'interfaceExplainFunc' => '接口功能',
                'interfaceExplainDetail' => '功能详述',
                'interfaceExplainParams' => '调用参数说明',
                'interfaceExplainResult' => '返回值说明',
                'interfaceExplainException' => '异常说明',
                'propertyException' => '模块[%s]缺少注解属性@jid-',
            ],
            'en' => [
                'loginButton' => 'login',
                'loginCancelButton' => 'cancel',
                'loginModal' => 'login',
                'logoutBtn' => 'logout',
                'navbar' => 'navbar',
                'panelTitle' => 'interface',
                'interfaceError' => 'no controller selected or get params error',
                'invokeButton' => 'invoke',
                'invokeXhprofBtn' => 'Xhprof invoke',
                'resultMessage' => 'result message',
                'noResultMessage' => 'not invoke or error',
                'interfaceExplain' => 'interface explain',
                'interfaceUri' => 'interface uri',
                'interfaceExplainMethod' => 'http method',
                'interfaceExplainFunc' => 'interface explain',
                'interfaceExplainDetail' => 'interface detail',
                'interfaceExplainParams' => 'interface params',
                'interfaceExplainResult' => 'result detail',
                'interfaceExplainException' => 'exceptions explain',
                'propertyException' => 'module[%s] not found note like @jid-',
            ],
        ];
    }
}