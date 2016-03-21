<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="modal-dialog modal-dialog-small animated fadeIn">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><strong>Join</strong> the network</h4>
        </div>
        <div class="modal-body">
            <br/>

            <?php if ($canRegister) : ?>
                <div class="text-center">
                    <ul id="tabs" class="nav nav-tabs tabs-center" data-tabs="tabs">
                        <li class="<?php echo (!isset($_POST['AccountRegister'])) ? "active" : ""; ?> tab-login"><a
                                href="#login"
                                data-toggle="tab"><?php echo Yii::t('SpaceModule.views_space_invite', 'Login'); ?></a>
                        </li>
                        <li class="<?php echo (isset($_POST['AccountRegister'])) ? "active" : ""; ?> tab-register"><a
                                href="#register"
                                data-toggle="tab"><?php echo Yii::t('SpaceModule.views_space_invite', 'New user?'); ?></a>
                        </li>
                    </ul>
                </div>
                <br/>
            <?php endif; ?>


            <div class="tab-content">
                <div class="tab-pane <?php echo (!isset($_POST['AccountRegister'])) ? "active" : ""; ?>" id="login">

                    <?php $form = ActiveForm::begin(); ?>


                    <p><?php echo Yii::t('UserModule.views_auth_login', "If you're already a member, please login with your username/email and password."); ?></p>

                    <?php echo $form->field($model, 'username')->textInput(['id' => 'login_username', 'placeholder' => Yii::t('UserModule.views_auth_login', 'username or email')]); ?>

                    <?php echo $form->field($model, 'password')->passwordInput(['id' => 'login_password', 'placeholder' => Yii::t('UserModule.views_auth_login', 'password')]); ?>


                    <?php echo $form->field($model, 'rememberMe')->checkbox(); ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <?php
                            echo \humhub\widgets\AjaxButton::widget([
                                'label' => Yii::t('UserModule.views_auth_login', 'Sign in'),
                                'ajaxOptions' => [
                                    'type' => 'POST',
                                    'beforeSend' => new yii\web\JsExpression('function(){ setModalLoader(); }'),
                                    'success' => 'function(html){ $("#globalModal").html(html); }',
                                    'url' => Url::to(['/user/auth/login']),
                                ],
                                'htmlOptions' => [
                                    'class' => 'btn btn-primary',
                                    'id' => 'loginBtn'
                                ]
                            ]);?>
                        </div>
                        <div class="col-md-5">
                            <a class="btn btn-primary btn-social btn-facebook" href="/social_login/social/auth?authclient=facebook"
                               onclick="window.open('/social_login/social/auth?authclient=facebook', 'newwindow', 'width=860, height=480'); return false;">
                                Sign in with Facebook
                            </a>
                        </div>

                        <div class="col-md-5 text-right">
                            <small>
                                <?php echo Yii::t('UserModule.views_auth_login', 'Forgot your password?'); ?>
                                <br/>
                                <?php
                                echo \humhub\widgets\AjaxButton::widget([
                                    'label' => Yii::t('UserModule.views_auth_login', 'Create a new one.'),
                                    'tag' => 'a',
                                    'ajaxOptions' => [
                                        'type' => 'POST',
                                        'beforeSend' => new yii\web\JsExpression('function(){ setModalLoader(); }'),
                                        'success' => 'function(html){ $("#globalModal").html(html); }',
                                        'url' => Url::to(['/user/auth/recover-password']),
                                    ],
                                    'htmlOptions' => [
                                        'id' => 'recoverPasswordBtn'
                                    ]
                                ]);
                                ?>
                            </small>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>

                <?php if ($canRegister) : ?>
                    <div class="tab-pane <?php echo (isset($_POST['AccountRegister'])) ? "active" : ""; ?>"
                         id="register">

                        <p><?php echo Yii::t('UserModule.views_auth_login', "Don't have an account? Join the network by entering your e-mail address."); ?></p>
                        <?php $form = ActiveForm::begin(); ?>

                        <?php echo $form->field($registerModel, 'email')->textInput(['id' => 'register-email', 'placeholder' => Yii::t('UserModule.views_auth_login', 'email')]); ?>
                        <hr>

                        <?php
                        echo \humhub\widgets\AjaxButton::widget([
                            'label' => Yii::t('UserModule.views_auth_login', 'Register'),
                            'ajaxOptions' => [
                                'type' => 'POST',
                                'beforeSend' => new yii\web\JsExpression('function(){ setModalLoader(); }'),
                                'success' => 'function(html){ $("#globalModal").html(html); }',
                                'url' => Url::to(['/user/auth/login']),
                            ],
                            'htmlOptions' => [
                                'class' => 'btn btn-primary', 'id' => 'registerBtn'
                            ]
                        ]);
                        ?>

                        <?php ActiveForm::end(); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<script type="text/javascript">
    // Replace the standard checkbox and radio buttons
    $('body').find(':checkbox, :radio').flatelements();


    $(document).ready(function () {
        $('#login_username').focus();

    });

    $('.tab-register a').on('shown.bs.tab', function (e) {
        $('#register-email').focus();
    })

    $('.tab-login a').on('shown.bs.tab', function (e) {
        $('#login_username').focus();
    })

</script>