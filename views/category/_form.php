<?php
use yii\easyii2\helpers\Image;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii2\widgets\SeoForm;

$class = $this->context->categoryClass;
$settings = $this->context->module->settings;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
<?= $form->field($model, 'title') ?>

<?php if(!empty($parent)) : ?>
    <div class="form-group field-category-title required">
        <label for="category-parent" class="control-label"><?= Yii::t('easyii2', 'Parent category') ?></label>
        <select class="form-control" id="category-parent" name="parent">
            <option value="" class="smooth"><?= Yii::t('easyii2', 'No') ?></option>
            <?php foreach($class::find()->sort()->asArray()->all() as $node) : ?>
                <option
                    value="<?= $node['category_id'] ?>"
                    <?php if($parent == $node['category_id']) echo 'SELECTED' ?>
                    style="padding-left: <?= $node['depth']*20 ?>px;"
                ><?= $node['title'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php endif; ?>

<?php if($settings['categoryThumb']) : ?>
    <?php if($model->image) : ?>
        <img src="<?= Image::thumb($model->image, 240) ?>">
        <a href="<?= Url::to(['/admin/'.$this->context->moduleName.'/a/clear-image', 'id' => $model->primaryKey]) ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii2', 'Clear image')?>"><?= Yii::t('easyii2', 'Clear image')?></a>
    <?php endif; ?>
    <?= $form->field($model, 'image')->fileInput() ?>
<?php endif; ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii2', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>