<?php
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>
<?php if(IS_ROOT) : ?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= $this->context->getReturnUrl(['/admin/'.$module]) ?>">
            <?php if($action === 'edit') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            <?= Yii::t('easyii2', 'List') ?>
        </a>
    </li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/a/create']) ?>"><?= Yii::t('easyii2/faq', 'Create entry') ?></a></li>
</ul>
<br/>
<?php elseif($action === 'edit') : ?>
    <ul class="nav nav-pills">
        <li>
            <a href="<?= $this->context->getReturnUrl(['/admin/'.$module])?>">
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
                <?= Yii::t('easyii2/faq', 'FAQ') ?>
            </a>
        </li>
    </ul>
    <br/>
<?php endif; ?>
