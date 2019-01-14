<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<style>
    body {
        background-color: #eee;
        padding-bottom: 40px;
        padding-top: 40px;
    }

    .form-signin {
        margin: 0 auto;
        max-width: 330px;
        padding: 15px;
    }

    .form-signin .form-signin-heading, .form-signin .checkbox {
        margin-bottom: 10px;
    }

    .form-signin .checkbox {
        font-weight: normal;
    }

    .form-signin .form-control {
        box-sizing: border-box;
        font-size: 16px;
        height: auto;
        padding: 10px;
        position: relative;
    }

    .form-signin .form-control:focus {
        z-index: 2;
    }

    .form-signin input[type="email"] {
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
        margin-bottom: -1px;
    }

    .form-signin input[type="password"] {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        margin-bottom: 10px;
    }
</style>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['role' => 'form', 'class' => 'form-signin'],
    'fieldConfig' => [
        'template' => "{input}\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]); ?>
<h2 class="form-signin-heading">请登录</h2>
<?php echo $form->field($model, 'password')->passwordInput(); ?>
<?php echo Html::submitButton('登录', ['class' => 'btn btn-lg btn-primary btn-block']); ?>

<?php ActiveForm::end(); ?>

