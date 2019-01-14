<?php
/**
 * Created by PhpStorm.
 * User: chenhongwei
 * Date: 2015/9/25
 * Time: 10:16
 */

namespace app\components\jid\controllers;

use yii\web\Controller;
use Yii;
use yii\web\Response;

class UpdateController extends Controller
{

    protected function getLogFile()
    {
        return Yii::$app->params['updateLog'];
    }

    protected function updated()
    {
        $key = 'updated_log_hash';
        $old_md5 = isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
        $md5 = md5_file($this->getLogFile());
        return $old_md5 === $md5;
    }

    protected function updateCookie()
    {
        $key = 'updated_log_hash';
        $md5 = md5_file($this->getLogFile());
        setcookie($key, $md5, time()+365*86400);
    }

    /**
     * 检查日志是否更新
     */
    public function actionChecking()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if($this->updated()){
            echo '{"updated":false}';
        }else{
            echo '{"updated":true}';
        }
    }

    /**
     * 获取日志
     */
    public function actionLog()
    {
        $this->updateCookie();
        $lines = file($this->getLogFile());
        $logs = [];
        $key = '';
        foreach($lines as $line)
        {
            if(preg_match('/^\d+年\d+月\d+日\d+:\d+:\d+/', $line)){
                $key = $line;
            }elseif(!empty($key)){
                $logs[$key][] = $line;
            }
        }

        return $this->renderPartial('logs', ['logs' => $logs, 'in'=>'in']);
    }
}