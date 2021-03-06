<?php
namespace yii\easyii2\modules\article\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii2\behaviors\SeoBehavior;
use yii\easyii2\behaviors\Taggable;
use yii\easyii2\components\ActiveRecord;
use yii\easyii2\models\Photo;
use yii\helpers\StringHelper;

/**
 * Class Item
 * @package yii\easyii2\modules\article\models
 *
 *
 * @property int $item_id [int(11)]
 * @property int $category_id [int(11)]
 * @property string $title [varchar(128)]
 * @property string $image [varchar(128)]
 * @property string $short [varchar(1024)]
 * @property string $text
 * @property string $slug [varchar(128)]
 * @property int $time [int(11)]
 * @property int $views [int(11)]
 * @property bool $status [tinyint(1)]
 *
 * @property-ready Category $category
 * @property-ready Photo[] $photos
 *
 * @property string $tagNames
 */
class Item extends \yii\easyii2\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public static function tableName()
    {
        return 'easyii2_article_items';
    }

    public function rules()
    {
        return [
            [['text', 'title', 'slug'], 'required'],
            [['title', 'short', 'text'], 'trim'],
            ['title', 'string', 'max' => 128],
            ['image', 'image'],
            [['category_id', 'views', 'time', 'status'], 'integer'],
            ['time', 'default', 'value' => time()],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii2', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['status', 'default', 'value' => self::STATUS_ON],
            ['tagNames', 'safe'],
            ['slug', 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii2', 'Title'),
            'text' => Yii::t('easyii2', 'Text'),
            'short' => Yii::t('easyii2/article', 'Short'),
            'image' => Yii::t('easyii2', 'Image'),
            'time' => Yii::t('easyii2', 'Date'),
            'slug' => Yii::t('easyii2', 'Slug'),
            'tagNames' => Yii::t('easyii2', 'Tags'),
        ];
    }

    public function behaviors()
    {
        return [
            'seoBehavior' => SeoBehavior::className(),
            'taggabble' => Taggable::className(),
        ];
    }

    public function getCategory()
    {
        $model = ActiveRecord::getModelByName('Category', 'article');
        return $this->hasOne($model::className(), ['category_id' => 'category_id']);
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'item_id'])->where(['class' => self::className()])->sort();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $settings = Yii::$app->getModule('admin')->getModule('article')->settings;
            $this->short = StringHelper::truncate($settings['enableShort'] ? $this->short : strip_tags($this->text), $settings['shortMaxLength']);

            if(!$insert && $this->image != $this->oldAttributes['image'] && $this->oldAttributes['image']){
                @unlink(Yii::getAlias('@webroot').$this->oldAttributes['image']);
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if($this->image){
            @unlink(Yii::getAlias('@webroot').$this->image);
        }

        foreach($this->getPhotos()->all() as $photo){
            $photo->delete();
        }
    }
}