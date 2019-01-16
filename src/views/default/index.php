<?php

use landrain\models\Language;
use \yii\helpers\Html;

$this->title = $this->context->module->name;
?>
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= $this->title; ?></h4>
            </div>
            <div class="modal-body">
                <form action="<?= $this->context->module->loginConfig['loginUrl'] ?>" method="post" role="form"
                      class="form-horizontal" id="loginForm">
                    <?php foreach ($this->context->module->loginConfig['fieldMapping'] as $key => $item) { ?>
                        <div class="form-group">
                            <label for="loginForm-account" class="col-sm-2 control-label"><?php echo $item; ?></label>
                            <div class="col-sm-9"><input type="text" name="<?= $key; ?>" id="loginForm-account"
                                                         class="form-control"/></div>
                        </div>
                    <?php } ?>

                    <input type="hidden" name="c_identity" id="loginForm-c_identity"/>
                    <input type="hidden" name="c_business" value="1"/>
                    <input type="hidden" name="sign" id="loginForm-signInput"/>
                    <div class="form-group" style="margin-bottom: 0;">
                        <div id="loginErrorText" class="alert alert-danger col-sm-6 col-sm-push-3"
                             style="margin-bottom: 0;display: none;" role="alert">

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Language::t('loginCancelButton');?></button>
                <button type="button" class="btn btn-primary" id="loginBtn"><?= Language::t('loginButton');?></button>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="row">
        <nav role="navigation" class="navbar navbar-default">
            <div class="navbar-header">
                <button data-target="#example-navbar-collapse" data-toggle="collapse" class="navbar-toggle"
                        type="button">
                    <span class="sr-only"><?= Language::t('navbar');?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="#" class="navbar-brand"><?= $title ?></a>
            </div>
            <div id="example-navbar-collapse" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                    </li>
                    <li><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                    <?php foreach ($modules as $name => $m): ?>
                        <?= Html::tag('li', Html::a($m['name'], ['/jid/default/index', 'parent' => '', 'module' => $name]), ['class' => $name == $module ? 'active' : '']) ?>
                    <?php endforeach ?>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <!--                <li><a href="/wiki/" title="wiki" target="_blank"><b class="text-danger">WIKI</b></a></li>-->
                    <li id="loginBtn" <?= !empty($loginInfo) ? 'style="display:none"' : ''; ?>><a href="#"
                                                                                                  data-toggle="modal"
                                                                                                  data-target="#loginModal"><?= Language::t('loginModal');?></a>
                    </li>
                    <li id="accountBtn" <?= empty($loginInfo) ? 'style="display:none"' : ''; ?> class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                           id="loginInfo-account"><?= !empty($loginInfo['account']) ? $loginInfo['account'] : 'ACCOUNT' ?>
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a id="loginInfo-uid"><?= !empty($loginInfo['uid']) ? $loginInfo['uid'] : 'UID' ?></a>
                            </li>
                            <li><a id="loginInfo-sid"><?= !empty($loginInfo['sid']) ? $loginInfo['sid'] : 'SID' ?></a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="<?php echo $this->context->module->logoutUrl; ?>" id="logoutBtn"><?= Language::t('logoutBtn');?></a></li>
                        </ul>
                    </li>

                    <?php
                    if (!empty($this->context->module->dropdownList)) {
                        foreach ($this->context->module->dropdownList as $name=>$src) { ?>
                            <li id="" class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" id=""><?= $name ?>
                                    <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><img src="<?= $src; ?>"/></li>
                                    <li class="divider"></li>
                                </ul>
                            </li>
                        <?php }
                    }
                    ?>
                </ul>
            </div>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-2">
            <div class="panel-group" id="accordion2">
                <?php foreach ($controllers as $cname => $c): ?>
                    <div class="panel panel-info">
                        <div class="panel-heading" data-toggle="collapse" data-parent="#accordion2"
                             href="#collapse<?= str_replace('\\', '-', $cname) ?>">
                            <a href="#" class="accordion-toggle"
                               style="text-decoration:none;display: block;outline: none"
                               title="<?= '【controller】' . $cname ?>"><?= $module . $c['brief']; ?></a>
                        </div>
                        <div id="collapse<?= str_replace('\\', '-', $cname) ?>"
                             class="list-group panel-collapse collapse<?= !strcasecmp($controller, $cname) ? ' in' : '' ?>">
                            <?php
                            foreach ($c['actions'] as $aname => $a) {
                                $apiPath        = explode("\\", $cname);
                                $moduleName     = isset($apiPath["3"]) ? $apiPath["3"] : "";
                                $controllerName = isset($apiPath["5"]) ? $apiPath["5"] : "";
                                $actionName     = $aname;
                                $apiUri         = "/" . $moduleName . "/" . $controllerName . "/" . $actionName;
                                ?>
                                <?php
                                echo Html::a($a['brief'] . $apiUri,
                                    ['/jid/default/index', 'parent' => '', 'module' => $module, 'controller' => $cname, 'action' => $aname],
                                    ['title' => '【controller】' . $cname . "\n" . '【action】' . $aname, 'id' => 'collapse' . $aname, 'class' => 'list-group-item' . ($action == $aname ? ' active' : '')])
                                ?>
                            <?php } ?>
                        </div>
                    </div>
                <?php endforeach ?>

            </div>
        </div>

        <div id="ouputPannel" class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Language::t('panelTitle')?></h3>
                </div>
                <div class="panel-body">
                    <?php if (empty($controller) || empty($action)): ?>
                        <h3 class="panel-title"><?= Language::t('interfaceError');?></h3>
                    <?php else: ?>
                        <form id="invokeForm" class="form-horizontal" role="form"
                              method="<?= trim(str_replace('<br />', '', $method)) ?>" action="
                    <?php
                        $url = \Yii::$app->urlManager->createAbsoluteUrl('' . '/' . lcfirst($module) . '/' . lcfirst($shortController) . '/' . lcfirst($action));
                        $url = str_ireplace('/-', '/', preg_replace_callback('/[A-Z]/', function ($match) {
                            return '-' . strtolower($match[0]);
                        }, $url));
                        echo $url;
                        ?>" enctype="multipart/form-data">
                            <?php foreach ($params as $i => $p): ?>
                                <div class="form-group">
                                    <?= Html::label($p['brief'], "param-{$i}-{$p['name']}", ['class' => 'col-sm-2 control-label']) ?>
                                    <div class="col-sm-9">
                                        <?php if (!empty($loginInfo['uid']) && $p['name'] == 'uid') {
                                            echo Html::textInput($p['name'], $loginInfo['uid'],
                                                ['class' => 'form-control', 'id' => "param-{$i}-{$p['name']}", 'placeholder' => $p['type'] . ' ' . $p['name']]);
                                        } else if (!empty($loginInfo['sid']) && $p['name'] == 'sid') {
                                            echo Html::textInput($p['name'], $loginInfo['sid'],
                                                ['class' => 'form-control', 'id' => "param-{$i}-{$p['name']}", 'placeholder' => $p['type'] . ' ' . $p['name']]);
                                        } else {
                                            if ($p['type'] == 'file') {
                                                echo '<input type="file" onclick="" name="' . $p['name'] . '" placeholder="" value="">';
                                            } elseif ($p['type'] == 'files') {
                                                echo '<input type="file" onclick="" name="' . $p['name'] . '[]" placeholder="" value="">';
                                                echo '<input type="file" onclick="" name="' . $p['name'] . '[]" placeholder="" value="">';
                                                echo '<input type="file" onclick="" name="' . $p['name'] . '[]" placeholder="" value="">';
                                            } else {
                                                $defaultValue = rtrim(ltrim($p['default'], "("), ")");
                                                echo Html::textInput($p['name'], $defaultValue,
                                                    ['class' => 'form-control', 'id' => "param-{$i}-{$p['name']}", 'placeholder' => $p['type'] . ' ' . $p['name']]);
                                            }
                                        } ?>

                                    </div>
                                </div>
                            <?php endforeach ?>

                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-10">
                                    <button type="button" class="btn btn-danger" id="invokeBtn"><?= Language::t('invokeButton');?></button>
                                    <button type="button" class="btn btn-danger" id="invokeXhprofBtn"><?= Language::t('invokeXhprofBtn');?></button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <a href="" id="apiUrl" style="word-break: break-all;" target="_blank"></a>
                                </div>
                            </div>
                        </form>
                    <?php endif ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <ul class="nav nav-pills">
                        <li class="active"><a href="#result" data-toggle="tab"><?= Language::t('resultMessage');?> </a></li>
                        <li style="float:right;">
                            <a id="outputExpand" href="javascript:;" style="color:gray">展开&gt;&gt;</a>
                            <a id="outputCollapse" href="javascript:;" style="color:gray;display: none">&lt;&lt;缩回</a>
                        </li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="result"><?= Language::t('noResultMessage');?></div>
                        <div class="tab-pane" id="thrift"><?= Language::t('noResultMessage');?></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="descPannel" class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Language::t('interfaceExplain');?></h3>
                </div>
                <div class="panel-body">
                    <p><span style="display: inline-block;padding-right: 5px;font-weight: bold;"><?= Language::t('interfaceUri');?>:</span>
                        <?php
                        if (!empty($url)) {
                            echo substr($url, strpos($url, '/', 7) + 1);
                        }
                        ?></p>
                    <p>
                        <span style="display: inline-block;padding-right: 5px;font-weight: bold;"><?= Language::t('interfaceExplainMethod');?>:</span><?php echo $method ?>
                    </p>
                    <p>
                        <span style="display: inline-block;padding-right: 5px;font-weight: bold;"><?= Language::t('interfaceExplainFunc');?>:</span><?php echo $brief ?>
                    </p>
                    <p>
                        <span style="display: inline-block;padding-right: 5px;font-weight: bold;"><?= Language::t('interfaceExplainDetail');?>:</span><?php echo str_replace('<br&nbsp;/>', '<br />', str_replace(' ', '&nbsp;', $function)); ?>
                    </p>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Language::t('interfaceExplainParams');?></h3>
                </div>
                <div class="panel-body">
                    <?php if (!empty($params)): ?>
                        <?php foreach ($params as $i => $p): ?>
                            <?= '$' .$p['name'] . ' ' . $p['brief'] . ' :' . $p['detail']  . '<br />' ?>
                        <?php endforeach ?>
                    <?php else: ?>
                        <?= Language::t('interfaceError'); ?>
                    <?php endif ?>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Language::t('interfaceExplainResult');?></h3>
                </div>
                <div class="panel-body">
                    <?php if (!empty($return)) {
                        echo str_replace('<br&nbsp;/>', '<br />', str_replace(' ', '&nbsp;', $return));
                    } else {
                        echo 'undefined';
                    } ?>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title" style="font-weight: bold;color:red"><?= Language::t('interfaceExplainException');?></h3>
                </div>
                <div class="panel-body">
                    <?php if (!empty($exception)) {
                        echo str_replace('<br&nbsp;/>', '<br />', str_replace(' ', '&nbsp;', $exception));
                    } else {
                        echo 'undefined';
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        var userLoginUrl = '<?=\Yii::$app->urlManager->createUrl('/jid/default/userlogin')?>';
        var folder = '<?=$this->context->module->assetsUrl?>';

        $('#invokeXhprofBtn').click(function (e) {
            var loadIndex = "";
            var options = {
                beforeSubmit: function (paramsObj) {
                    var formActionUrl = $("#invokeForm").attr("action");
                    paramsObj.xhprof = 1;
                    reStoreInputVal(paramsObj, formActionUrl);
                    loadIndex = layer.load(1);
                },
                success: function (resp) {
                    layer.close(loadIndex);
                    $QueryUrl = $(this)[0]['url'];
                    $("#apiUrl").attr("href", $QueryUrl + '&xhprof=1');
                    $("#apiUrl").html("xhprof invoke");

                    new JsonFormater({
                        dom: '#result',
                        imgCollapsed: folder + "/jsonFormater/images/Collapsed.gif",
                        imgExpanded: folder + "/jsonFormater/images/Expanded.gif"
                    }).doFormat(resp);
                },
                error: function (error) {
                    layer.close(loadIndex);
                    var status = error.status;
                    var statusText = error.statusText;
                    var errorMsg = "ErrorCode:" + status + '，ErrorMsg:' + statusText;
                    layer.msg(errorMsg);
                }
            };
            $('#invokeForm').ajaxSubmit(options);
        });

        $('#invokeBtn').click(function (e) {
            $("#result").html("");
            e.preventDefault();
            var loadIndex = "";
            var options = {
                beforeSubmit: function (paramsObj) {
                    var formActionUrl = $("#invokeForm").attr("action");
                    reStoreInputVal(paramsObj, formActionUrl);
                    loadIndex = layer.load(1);
                },
                success: function (resp) {
                    layer.close(loadIndex);
                    $QueryUrl = $(this)[0]['url'];
                    $("#apiUrl").attr("href", $QueryUrl);
                    $("#apiUrl").html($QueryUrl);

                    new JsonFormater({
                        dom: '#result',
                        imgCollapsed: folder + "/jsonFormater/images/Collapsed.gif",
                        imgExpanded: folder + "/jsonFormater/images/Expanded.gif"
                    }).doFormat(resp);
                },
                error: function (error) {
                    layer.close(loadIndex);
                    var status = error.status;
                    var statusText = error.statusText;
                    var errorMsg = "ErrorCode:" + status + '，ErrorMsg:' + statusText;
                    layer.msg(errorMsg);
                }
            };
            $('#invokeForm').ajaxSubmit(options);
        })
    });

    $('#outputExpand').click(function () {
        $('#ouputPannel').addClass('col-md-10').removeClass('col-md-5');
        $('#descPannel').hide();
        $(this).hide();
        $('#outputCollapse').show();
    });
    $('#outputCollapse').click(function () {
        $('#ouputPannel').addClass('col-md-5').removeClass('col-md-10');
        $('#descPannel').show();
        $(this).hide();
        $('#outputExpand').show();
    });


    $('#loginBtn').click(function (e) {
        e.preventDefault();
        var timestamp = (new Date()).valueOf();
        $('#loginErrorText').hide();
        $('#loginForm').ajaxSubmit(function (result) {
            //var result = $.parseJSON(resp);
            if (result.res && result.ret == true) {
                var account = $('#loginForm-account').val();
                // console.log(sid);
                var sid = result.res.source.sid;
                var uid = result.res.source.uid;

                $('#loginInfo-account').text(account);
                $('#loginInfo-sid').text(sid);
                $('#loginInfo-uid').text(uid);

                $.get(userLoginUrl, {account: account, sid: sid, uid: uid});

                $('#accountBtn').show();
                $('#loginBtn').hide();

                $('#loginModal').modal('hide');
            } else {
                $('#loginErrorText')
                    .text(result.res.result.errMsg + '[' + result.res.result.errCode + ']')
                    .show();
            }
        });
    });
</script>

<script>
    function reStoreInputVal(paramsObj, apiUrl) {
        if (window.localStorage) {
            for (var key in paramsObj) {
                var inputName = paramsObj[key]['name'];
                var inputValue = paramsObj[key]['value'];
                var inputType = paramsObj[key]['type'];
                if (inputType == 'hidden') {
                    continue;
                }
                var localStorageKey = apiUrl + '_' + inputName;
                localStorage.setItem(localStorageKey, inputValue);
            }
        }
    }

    function rePutDataToInputFromlocalStorageData() {
        var inputObj = $('#invokeForm').find('input');
        if (!inputObj) {
            return false;
        }
        var formActionUrl = $("#invokeForm").attr("action");

        for (var i = 0; i < inputObj.length; i++) {
            var inputType = $(inputObj[i]).attr("type");
            var inputName = $(inputObj[i]).attr("name");
            if (inputType == 'hidden' || inputName == 'uid' || inputName == 'phone' || inputName == 'sid' || inputName == 'account') {
                continue;
            }

            var localStorageKey = formActionUrl + '_' + inputName;

            var cacheValue = localStorage.getItem(localStorageKey);
            if (cacheValue) {
                $(inputObj[i]).val(cacheValue);
            }
        }
    }

    rePutDataToInputFromlocalStorageData();
</script>