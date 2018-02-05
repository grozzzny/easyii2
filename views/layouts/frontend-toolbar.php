<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\easyii2\assets\FrontendAsset;
use yii\easyii2\models\Setting;

$asset = FrontendAsset::register($this);
$position = Setting::get('toolbar_position') === 'bottom' ? 'bottom' : 'top';
$this->registerCss('body {padding-'.$position.': 50px;}');
$this->registerCss('
#easyii2-navbar{
    background-color: #303030;
    border: none;
}
#easyii2-navbar .navbar-left li:first-child {
    border-right: 1px solid rgba(255, 255, 255, 0.22);
}
#easyii2-navbar a, #easyii2-navbar .navbar-text {
    color: #ffffff;
}
#easyii2-navbar .switcher {
    background: #ebebeb;
}
@media only screen and (max-width: 768px) {
  #easyii2-navbar li a, #easyii2-navbar .navbar-text {
    width: 16px;
    overflow: hidden;
    white-space: nowrap;
  }
  #easyii2-navbar .container{
    display: flex;
    justify-content: space-between;
  }
  #easyii2-navbar .container > *{
    float:left;
    margin-right:30px;
  }
  #easyii2-navbar .navbar-left li:first-child {
    border: none;
  }
  #easyii2-navbar .navbar-text {
    display:none;
  }
  #easyii2-navbar .switcher {
    margin-top: 17px;
  }
}
');
?>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<nav id="easyii2-navbar" class="navbar navbar-inverse navbar-fixed-<?= $position ?>">
    <div class="container">
        <ul class="nav navbar-nav navbar-left">
            <li><a href="<?= Url::to(['/admin']) ?>"><span class="glyphicon glyphicon-arrow-left"></span> <?= Yii::t('easyii2', 'Control Panel') ?></a></li>
        </ul>
        <p class="navbar-text"><i class="glyphicon glyphicon-pencil"></i> <?= Yii::t('easyii2', 'Live edit') ?></p>
        <?= Html::checkbox('', LIVE_EDIT, ['data-link' => Url::to(['/admin/system/live-edit'])]) ?>

        <ul class="nav navbar-nav navbar-right">
            <li><a href="<?= Url::to(['/admin/sign/out']) ?>"><span class="glyphicon glyphicon-log-out"></span> <?= Yii::t('easyii2', 'Logout') ?></a></li>
        </ul>
    </div>
</nav>