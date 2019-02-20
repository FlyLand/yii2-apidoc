<?php

namespace landrain\apidoc;

use landrain\apidoc\models\Language;
use landrain\apidoc\models\User;

class Module extends \yii\base\Module
{
    /**
     * @var string the password that can be used to access jidModule.
     * If this property is set false, then jidModule can be accessed without password
     * (DO NOT DO THIS UNLESS YOU KNOW THE CONSEQUENCE!!!)
     */
    public $password;

    /**
     * @var array the IP filters that specify which IP addresses are allowed to access jidModule.
     * Each array element represents a single filter. A filter can be either an IP address
     * or an address with wildcard (e.g. 192.168.0.*) to represent a network segment.
     * If you want to allow all IPs to access jid, you may set this property to be false
     * (DO NOT DO THIS UNLESS YOU KNOW THE CONSEQUENCE!!!)
     * The default value is array('127.0.0.1', '::1'), which means jidModule can only be accessed
     * on the localhost.
     */
    public $ipFilters = array('127.0.0.1', '::1');

    /**
     * @var string 调试器名称，显示在左上角
     */
    public $name;

    /**
     * @var array 登陆接口配置
     */
    public $loginConfig = [
        'loginUrl'        => '/app/common/login',
        'fieldMapping'    => [
            'account'  => 'account',
            'password' => 'password',
            'email'    => 'email'
        ],
    ];

    /**
     * @var string 签名获取接口
     */
    public $signUrl;

    public $secretKey = array();

    private $_assetsUrl;

    public $xhprofUrl;

    public $dropdownList;

    public $logoutUrl;

    public $subOfClasses;

    public $language = 'zh';

    /**
     * Initializes the jid module.
     */
    public function init()
    {
        User::$passwordSetting = $this->password;
        Language::$lang = strtolower($this->language);
        parent::init();
        \Yii::$app->setComponents(array(
            'errorHandler' => array(
                'class'       => '\yii\web\ErrorHandler',
                'errorAction' => $this->id . '/default/error',
            ),
            'user'         => array(
                'class'         => 'yii\web\User',
                'identityClass' => 'landrain\apidoc\models\User',
                'loginUrl'      => \Yii::$app->urlManager->createUrl($this->id . '/default/login'),
            ),
        ), false);
    }

    /**
     * @return string the base URL that contains all published asset files of jid.
     */
    public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = \Yii::$app->getAssetManager()->publish(\Yii::getAlias('@vendor/landrain/yii2-apidoc/src/assets'))[1];
        return $this->_assetsUrl;
    }

    /**
     * @param string $value the base URL that contains all published asset files of jid.
     */
    public function setAssetsUrl($value)
    {
        $this->_assetsUrl = $value;
    }

    /**
     * Performs access check to jid.
     * This method will check to see if user IP and password are correct if they attempt
     * to access actions other than "default/login" and "default/error".
     * @param \yii\base\Controller $controller the controller to be accessed.
     * @param \yii\base\Action $action the action to be accessed.
     * @return boolean whether the action should be executed.
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $route = \Yii::$app->controller->id . '/' . $action->id;
            //$route = \Yii::$app->controller->getRoute();
            if (!$this->allowIp(\Yii::$app->request->userIP) && $route !== 'default/error')
                throw new \yii\web\HttpException(403, "You are not allowed to access this page.");

            $publicPages = array(
                'default/login',
                'default/error',
            );
            if ($this->password !== false && \Yii::$app->user->isGuest && !in_array($route, $publicPages))
                \Yii::$app->user->loginRequired();
            else
                return true;
        }
        return false;
    }

    /**
     * Checks to see if the user IP is allowed by {@link ipFilters}.
     * @param string $ip the user IP
     * @return boolean whether the user IP is allowed by {@link ipFilters}.
     */
    protected function allowIp($ip)
    {
        if (empty($this->ipFilters))
            return true;
        foreach ($this->ipFilters as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos)))
                return true;
        }
        return false;
    }
}