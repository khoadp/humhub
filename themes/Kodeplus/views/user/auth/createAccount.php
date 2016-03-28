<?php

use yii\helpers\Html;
use humhub\modules\user\models\User;
use yii\base\Event;

$this->pageTitle = Yii::t('UserModule.views_auth_createAccount', 'Create Account');
?>
<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <?php echo \humhub\widgets\LoaderWidget::widget(); ?>
            </div>
        </div>
    </div>
</div>
<div class="container" style="text-align: center;">
    <h1 id="app-title" class="animated fadeIn"><?php echo Html::encode(Yii::$app->name); ?></h1>
    <br/>
    <div class="row">
        <div id="create-account-form" class="panel panel-default animated bounceIn"
             style="max-width: 500px; margin: 0 auto 20px; text-align: left;">
            <div
                class="panel-heading"><?php echo Yii::t('UserModule.views_auth_createAccount', '<strong>Account</strong> registration'); ?></div>
            <div class="panel-body">
                <?php $form = \yii\widgets\ActiveForm::begin(['enableClientValidation' => false]); ?>
                <?php echo $hForm->render($form); ?>
                <?php \yii\widgets\ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        // set cursor to login field
        $('#User_username').focus();
    })

    // Shake panel after wrong validation
    <?php foreach ($hForm->models as $model) : ?>
    <?php if ($model->hasErrors()) : ?>
    $('#create-account-form').removeClass('bounceIn');
    $('#create-account-form').addClass('shake');
    $('#app-title').removeClass('fadeIn');
    <?php endif; ?>
    <?php endforeach; ?>

    var html = '<div class="form-group field-user-term required">'
        + '<input  type="checkbox" id="terms" name="term_status"/> <?= Yii::t('KodeplusSpaceModule.term', 'By checking this box, you agree to the') ?> '
        + '<a href="#" class="term_view_btn"><?= Yii::t('KodeplusSpaceModule.term', 'terms and conditions') ?></a>.'
        + '<div class="help-block"></div>'
        + '</div>';
    $('.field-profile-lastname').after(html);
    $('form').on('submit', function (e) {
        if ($('#terms').prop('checked') === true) {
            $('.field-user-term').removeClass('has-error');
            $('.field-user-term .help-block').html('');


        } else {
            $('.field-user-term').addClass('has-error');
            $('.field-user-term .help-block').html('<?= Yii::t('KodeplusSpaceModule.term', 'You need to agree to our terms and conditions.') ?>');
            e.preventDefault();
        }

    });
    $('.term_view_btn').click(function () {
        $('#globalModal').modal({
            remote: '/kodeplus_user/term-show/show',
            show: true,
        });
    });
</script>
