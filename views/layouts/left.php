<?
use yii\easyii2\AdminModule;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \yii\web\Controller $context
 */

$context = $this->context;
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="header">
                <?= Yii::t('easyii2', 'Settings') ?>:
            </li>
            <li class="treeview <?= in_array($context->id, ['modules', 'settings', 'admins', 'system', 'logs']) ? 'active' :'' ?>">
                <a href="#" class="menu-item ">
                    <i class="glyphicon glyphicon-cog"></i>
                    <span>
                        <?= Yii::t('easyii2', 'Settings') ?>
                    </span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($context->id == 'settings') ? 'active' :'' ?>">
                        <a href="<?= Url::to(['/admin/settings']) ?>">
                            <i class="glyphicon glyphicon-cog"></i>
                            <span>
                                <?= Yii::t('easyii2', 'Settings') ?>
                            </span>
                        </a>
                    </li>
                    <?php if(IS_ROOT) : ?>
                        <li class="<?= ($context->id == 'modules') ? 'active' :'' ?>">
                            <a href="<?= Url::to(['/admin/modules']) ?>">
                                <i class="glyphicon glyphicon-folder-close"></i>
                                <span>
                                    <?= Yii::t('easyii2', 'Modules') ?>
                                </span>
                            </a>
                        </li>
                        <li class="<?= ($context->id == 'admins') ? 'active' :'' ?>">
                            <a href="<?= Url::to(['/admin/admins']) ?>">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?= Yii::t('easyii2', 'Admins') ?><span>
                            </a>
                        </li>
                        <li class="<?= ($context->id == 'system') ? 'active' :'' ?>">
                            <a href="<?= Url::to(['/admin/system']) ?>">
                                <i class="glyphicon glyphicon-hdd"></i>
                                <span><?= Yii::t('easyii2', 'System') ?></span>
                            </a>
                        </li>
                        <li class="<?= ($context->id == 'logs') ? 'active' :'' ?>">
                            <a href="<?= Url::to(['/admin/logs']) ?>">
                                <i class="glyphicon glyphicon-align-justify"></i>
                                <span><?= Yii::t('easyii2', 'Logs') ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
            <li class="header"><?= Yii::t('easyii2', 'Modules') ?>:</li>
            <?php foreach(Yii::$app->getModule('admin')->allModules as $module) : ?>
            <li class="<?= ($context->module->id == $module->name ? 'active' : '') ?>">
                <a href="<?= Url::to(["/admin/$module->name"]) ?>">
                    <?php if($module->icon != '') : ?>
                        <i class="glyphicon glyphicon-<?= $module->icon ?>"></i>
                    <?php endif; ?>
                    <span>
                        <?= $module->getTitle() ?>
                    </span>
                    <?php if(isset($module->model->notice) && $module->model->notice > 0) : ?>
                    <span class="pull-right-container">
                      <span class="label label-primary pull-right"><?= $module->model->notice ?></span>
                    </span>
                    <?php endif; ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>

    </section>
</aside>
