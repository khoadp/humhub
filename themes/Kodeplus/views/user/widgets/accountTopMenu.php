<?php

use \yii\helpers\Html;
use \yii\helpers\Url;

?>
<?php if ( Yii::$app->user->isGuest ): ?>
    <a href="<?php echo Url::toRoute( '/user/auth/login' ); ?>" class="btn btn-enter" data-target="#globalModal">
	    <?php echo Yii::t('UserModule.widgets_views_accountTopMenu', 'Sign in / up'); ?>
    </a>
<?php else: ?>
	<?php
	$userTitle = Html::encode( Yii::$app->user->getIdentity()->profile->title );
	?>
    <ul class="nav">
        <li class="dropdown account">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="user-title pull-left hidden-xs" <?php if ( empty( $userTitle ) ) {
					echo 'style="line-height:2.3em"';
				} ?>>
                    <strong><?php echo Html::encode( Yii::$app->user->getIdentity()->displayName ); ?></strong>
                    <br/>
                    <span class="truncate"><?php echo $userTitle; ?></span>
                </div>

                <img id="user-account-image" class="img-rounded"
                     src="<?php echo Yii::$app->user->getIdentity()->getProfileImage()->getUrl(); ?>"
                     height="32" width="32" alt="32x32" data-src="holder.js/32x32"
                     style="width: 32px; height: 32px;"/>

                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu pull-right">
				<?php foreach ( $this->context->getItems() as $item ): ?>
					<?php if ( $item['label'] == '---' ): ?>
                        <li class="divider"></li>
					<?php else: ?>
                        <li>
                            <a href="<?php echo $item['url']; ?>">
								<?php echo $item['icon'] . ' ' . $item['label']; ?>
                            </a>
                        </li>
					<?php endif; ?>
				<?php endforeach; ?>
            </ul>
        </li>
    </ul>
<?php endif; ?>