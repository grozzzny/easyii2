<?php
use yii\easyii2\modules\shopcart\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('easyii2/shopcart', 'Order') . ' #' . $order->primaryKey;
$this->registerCss('.shopcart-view dt{margin-bottom: 10px;}');

$states = Order::states();
unset($states[Order::STATUS_BLANK]);

$module = $this->context->module->id;

$this->registerJs('
var oldStatus = '.$order->status.';
$("#order-status").change(function(){
    if($(this).val() != oldStatus){
        $("#notify-user").slideDown();
    } else {
        $("#notify-user").slideUp();
    }
});
');
?>
<?= $this->render('_menu') ?>

<?= Html::beginForm() ?>
<dl class="dl-horizontal shopcart-view">
    <?php if($order->status != Order::STATUS_BLANK) : ?>
        <dt><?= Yii::t('easyii2', 'Status') ?></dt>
        <dd>
            <div class="form-group">
                <?= Html::dropDownList('status', $order->status, $states, ['id' => 'order-status']) ?>
            </div>
        </dd>
    <?php endif; ?>

    <dt><?= Yii::t('easyii2', 'Name') ?></dt>
    <dd><?= $order->name ?></dd>

    <dt><?= Yii::t('easyii2/shopcart', 'Address') ?></dt>
    <dd><?= $order->address ?></dd>

    <?php if($this->context->module->settings['enablePhone']) : ?>
        <dt><?= Yii::t('easyii2/shopcart', 'Phone') ?></dt>
        <dd><?= $order->phone ?></dd>
    <?php endif; ?>

    <?php if($this->context->module->settings['enableEmail']) : ?>
        <dt><?= Yii::t('easyii2', 'E-mail') ?></dt>
        <dd><?= $order->email ?></dd>
    <?php endif; ?>

    <dt><?= Yii::t('easyii2', 'Date') ?></dt>
    <dd><?= Yii::$app->formatter->asDatetime($order->time, 'medium') ?></dd>

    <dt>IP</dt>
    <dd><?= $order->ip ?> <a href="//freegeoip.net/?q=<?= $order->ip ?>" class="label label-info" target="_blank">info</a></dd>

    <dt><?= Yii::t('easyii2/shopcart', 'Comment') ?></dt>
    <dd><?= nl2br($order->comment) ?></dd>

    <dt><?= Yii::t('easyii2/shopcart', 'Admin remark') ?></dt>
    <dd>
        <div class="form-group">
            <?= Html::textarea('remark', $order->remark, ['class' => 'form-control']) ?>
        </div>
        <?php if($order->email) : ?>
            <div class="checkbox" id="notify-user" style="display: none;">
                <label>
                    <?= Html::checkbox('notify', true, ['uncheck' => 0]) ?> <?= Yii::t('easyii2/shopcart', 'Notify user on E-mail') ?>
                </label>
            </div>
        <?php endif; ?>
        <?= Html::submitButton(Yii::t('easyii2', 'Save'), ['class' => 'btn btn-primary']) ?>
    </dd>
</dl>
<?= Html::endForm() ?>
<hr>
<h3><?= Yii::t('easyii2/shopcart', 'Items') ?></h3>
<table class="table table-bordered">
    <thead>
        <th><?= Yii::t('easyii2', 'Title') ?></th>
        <th><?= Yii::t('easyii2/shopcart', 'Options') ?></th>
        <th width="80"><?= Yii::t('easyii2/shopcart', 'Count') ?></th>
        <th width="80"><?= Yii::t('easyii2/shopcart', 'Discount') ?></th>
        <th width="150"><?= Yii::t('easyii2/shopcart', 'Price') ?></th>
        <th width="30"></th>
    </thead>
    <tbody>
        <?php foreach($goods as $good) : ?>
            <tr>
                <td><?= Html::a($good->item->title, ['/admin/catalog/items/edit', 'id' => $good->item->primaryKey]) ?></td>
                <td><?= $good->options ?></td>
                <td><?= $good->count ?></td>
                <td><?= $good->discount ?></td>
                <td>
                    <?php if($good->discount) : ?>
                        <b><?= round($good->price * (1 - $good->discount / 100)) ?></b>
                        <strike><small class="smooth"><?= $good->price ?></small></strike>
                    <?php else : ?>
                        <b><?= $good->price ?></b>
                    <?php endif; ?>
                </td>
                <td><a href="<?= Url::to(['/admin/'.$module.'/goods/delete', 'id' => $good->primaryKey]) ?>" class="glyphicon glyphicon-remove confirm-delete" title="<?= Yii::t('easyii2', 'Delete item') ?>"></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<h2 class="text-right"><small><?= Yii::t('easyii2/shopcart', 'Total') ?>:</small> <?= $order->cost ?></h2>
