<?php

namespace app\components\jid\controllers;

use \yii;

header('Content-type:text/html;charset=utf-8');

class DefaultController extends \yii\web\Controller
{
    public $layout = 'column1';

    public function getPageTitle()
    {
        if ($this->action->id === 'index')
            return 'Gii: a Web-based code generator for Yii';
        else
            return 'Gii - ' . ucfirst($this->action->id) . ' Generator';
    }

    public function actionError()
    {
        if ($error = \yii::$app->errorHandler->error) {
            if (\yii::$app->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * @brief 登入
     */
    public function actionLogin()
    {
        $model = new \app\components\jid\models\LoginForm();

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid

            if ($model->validate() && $model->login()) {
                $this->redirect(\yii::$app->urlManager->createUrl('jid/default/index'));
            }

        }
        // display the login form
        return $this->render('login', array('model' => $model));
    }

    /**
     * @brief 登出
     */
    public function actionLogout()
    {
        \yii::$app->user->logout(false);
        $this->redirect(\yii::$app->urlManager->createUrl('jid/default/index'));
    }

    public function actionIndex($parent = '', $module = 'user', $controller = '', $action = '')
    {
        $modules = $this->getReflectionModules($parent);
        if ($module == 'user' && !empty($modules) && empty($modules[$module])) $module = key($modules);
        if (empty($modules[$module])) throw new \yii\base\Exception(sprintf('未检测到模块[%s]', $module));

        $controllers = $this->getControllers($parent . $module);

        $params = isset($controllers[$controller]['actions'][$action]['param'])
            ? $controllers[$controller]['actions'][$action]['param']
            : [];

        $pos             = strrpos($controller, '\\');
        $shortController = substr($controller, $pos + 1);

        return $this->render('index', [
            'module'          => $module,
            'controller'      => $controller,
            'shortController' => $shortController,
            'action'          => $action,
            'modules'         => $modules,
            'controllers'     => $controllers,
            'method'          => isset($controllers[$controller]['actions'][$action]['method']) ?
                $controllers[$controller]['actions'][$action]['method'] : 'GET',
            'brief'           => isset($controllers[$controller]['actions'][$action]['brief']) ?
                $controllers[$controller]['actions'][$action]['brief'] : '未填写',
            'function'        => isset($controllers[$controller]['actions'][$action]['detail']) ?
                $controllers[$controller]['actions'][$action]['detail'] : '未填写',
            'params'          => $params,
            'return'          => isset($controllers[$controller]['actions'][$action]['return']) ?
                $controllers[$controller]['actions'][$action]['return'] : '未填写',
            'exception'       => isset($controllers[$controller]['actions'][$action]['throws']) ?
                $controllers[$controller]['actions'][$action]['throws'] : '未填写',
            'title'           => $this->module->name,
            'loginInfo'       => $this->isGuest() ? $_SESSION['loginInfo'] : []
        ]);
    }

    /**
     * 判断是否继承设定的classes
     * @param \ReflectionClass $rc
     * @return bool
     */
    private function isSubclassOfList(\ReflectionClass $rc)
    {
        if (!empty($this->module->subOfClasses)) {
            foreach ($this->module->subOfClasses as $subOfClass) {
                if (!class_exists($subOfClass)) continue;
                if (!$rc->isSubclassOf($subOfClass)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @brief 反射控制器
     * @return array
     * @throws \Exception
     */
    protected function getControllers($module)
    {
        $controllers = [];

        $dirName = Yii::getAlias('@app/modules/' . $module . '/controllers');
        if (!is_dir($dirName)) return [];
        $dirs = scandir($dirName);

        foreach ($dirs as $d) {
            if (preg_match('/^\..*/', $d)) continue;
            $actions = [];

            //每个module下的controllers类
            $class = '\app\modules\\' . str_replace('/', '\\', $module) . '\\controllers\\' . substr($d, 0, -4);

            $rc = new \ReflectionClass($class);

            if (!$this->isSubclassOfList($rc)) continue;

            $rm = $rc->getMethods(\ReflectionMethod::IS_PUBLIC);

            foreach ($rm as $m) {
                $name = $m->getName();
                if (!preg_match('/action*/', $m) || $name == 'actions') continue;

                if (!strncasecmp($name, 'action', 6) && $name != 'actions') {
                    $method = new \ReflectionMethod($class, $name);

                    $actions[substr($name, 6)] = array_merge([
                        'id'      => substr($name, 6),
                        'version' => 1,
                        'brief'   => '未填写brief'
                    ], $this->extractProperty($method->getDocComment()));
                    unset($method);
                }
            }
            $controllers[substr($class, 0, -10)] = array_merge([
                'id'      => substr($class, 0, -10),
                'actions' => $actions,
                'brief'   => '未填写brief'
            ], $this->extractProperty($rc->getDocComment()));
        }
        return $controllers;

    }


    /**
     * @brief 反射模块
     * @return array
     * @throws Exception
     */
    protected function getReflectionModules($parent = '')
    {
        $modules = [];

        $dirs = scandir(Yii::getAlias('@app/modules' . ($parent == '' ? '' : '/' . $parent . '/modules')));

        foreach ($dirs as $d) {
            if (preg_match('/^\..*/', $d)) continue;
            if (strpos($d, '.php')) continue;

            $rc = $this->getReflectionModuleClass($parent, $d);

            //检测是否存在开关标记
            if (!preg_match('/@jid-enable/', $rc->getDocComment())) continue;

            $properties = $this->extractProperty($rc->getDocComment(), 'jid-');

            foreach (['id', 'name'] as $i) {
                if (empty($properties[$i])) throw new \yii\base\Exception(sprintf('模块[%s]缺少注解属性@jid-%s', $d, $i));
            }

            $modules[$d] = $properties;
        }

        return $modules;
    }

    protected function getReflectionModuleClass($parent, $module)
    {
        $modClass = '\app\modules\\' . $module . '\Module';

        return new \ReflectionClass($modClass);
    }

    /**
     * @brief 提取注解属性
     * @param $comment
     * @param string $prefix
     * @return array
     */
    protected function extractProperty($comment, $prefix = '')
    {
        $properties = [];
        if (preg_match_all('/@' . $prefix . '([a-zA-Z]+)\b([^@]+)/u', $comment, $matches)) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                if (in_array($matches[1][$i], ['param'])) {
                    $properties[$matches[1][$i]][] = $this->extractParamInfo(str_replace('*', '', trim($matches[2][$i], '/')));
                } else {
                    $properties[$matches[1][$i]] = nl2br(preg_replace('/^\s*\n/', '', str_replace('*', '', trim($matches[2][$i], '/'))));
                }
            }
        }
        return $properties;

    }

    /**
     * @brief 提取参数
     */
    public function extractParamInfo($paramInfo)
    {
        if (empty($paramInfo)) return [];

        $param = [
            'type'    => 'unknown',
            'name'    => 'unknown',
            'default' => null,
            'brief'   => '未填写',
            'detail'  => ''
        ];

        $part = explode(' ', trim($paramInfo));
        if (!empty($part[0])) $param['type'] = $part[0];
        if (!empty($part[1])) $param['name'] = $part[1];
        if (!empty($part[2])) $param['brief'] = $part[2];
        if (!empty($part[3])) $param['detail'] = nl2br(implode(' ', array_slice($part, 3)));

        $param['name'] = str_replace('$', '', $param['name']);
        if (strpos($param['name'], '=')) {
            list($param['name'], $param['default']) = explode('=', $paramInfo);
        }

        return $param;
    }

    function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * @brief 记录用户登陆状态
     * @param $account
     * @param $uid
     * @param $sid
     */
    public function actionUserlogin($account, $uid, $sid)
    {
        $_SESSION['loginInfo'] = ['account' => $account, 'uid' => $uid, 'sid' => $sid];
    }

    /**
     * @brief 消除用户登陆状态
     * @param $account
     * @param $uid
     * @param $sid
     */
    public function actionUserlogout($jto)
    {
        $this->logout();
        $this->redirect($jto);
    }

    protected function logout()
    {
        unset($_SESSION['loginInfo']);
    }

    protected function isGuest()
    {
        if (!isset($_SESSION['loginInfo']['sid']) /*||  !\yii::$app->ocs->get($_SESSION['loginInfo']['sid'])*/) {
            $this->logout();
            return false;
        }
        return true;
    }
}