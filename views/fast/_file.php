<?
use yii\easyii2\helpers\Image;
use yii\helpers\Url;
use yii\web\View;
/**
 * @var View $this
 * @var \yii\easyii2\components\FastModelInterface|\yii\easyii2\components\FastModel $model
 * @var string $attribute
 */
?>

<? if($model->$attribute) : ?>
    <div>
        <a href="<?= $model->file ?>" target="_blank"><?= basename($model->file) ?></a>
        (<?= Yii::$app->formatter->asShortSize(filesize(Yii::getAlias('@webroot').$model->file), 2) ?>)
        <span> / </span>
        <a href="<?= Url::to([
            '/admin/'.Yii::$app->controller->module->id.'/a/clear-file',
            'id' => $model->id,
            'slug' => $model::getSlugModel(),
            'attribute' => 'file'
        ]) ?>" class="text-danger confirm-delete"><?=Yii::t('easyii2', 'Remove file')?></a>
    </div>
    <br>
<? endif; ?>