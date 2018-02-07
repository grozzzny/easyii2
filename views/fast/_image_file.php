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
    <div class="form-group">
        <img src="<?= Image::thumb($model->$attribute, 240) ?>">
    </div>
    <div class="form-group">
        <a href="<?= Url::to([
            '/admin/'.Yii::$app->controller->module->id.'/a/clear-file',
            'id' => $model->primaryKey,
            'slug' => $model::getSlugModel(),
            'attribute' => $attribute
        ]) ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii2', 'Clear image')?>"><?= Yii::t('easyii2', 'Clear image')?></a>
    </div>
<? endif; ?>