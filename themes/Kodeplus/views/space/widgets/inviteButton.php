<?php

use yii\helpers\Html;

echo Html::a('<i class="fa fa-plus"></i> '. Yii::t('KodeplusSpaceModule.base', 'Invite'), $space->createUrl('/space/membership/invite'), array('class' => 'btn btn-primary', 'data-target' => '#globalModal'));
