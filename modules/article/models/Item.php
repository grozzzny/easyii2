<?php
namespace yii\easyii2\modules\article\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii2\behaviors\SeoBehavior;
use yii\easyii2\behaviors\Taggable;
use yii\easyii2\models\Photo;
use yii\helpers\StringHelper;

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
            [['text', 'title'], 'required'],
            [['title', 'short', 'text'], 'trim'],
            ['title', 'string', 'max' => 128],
            ['image', 'image'],
            [['category_id', 'views', 'time', 'status'], 'integer'],
            ['time', 'default', 'value' => time()],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii2', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['status', 'default', 'value' => self::STATUS_ON],
            ['tagNames', 'safe']
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
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ]
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'item_id'])->where(['class' => self::className()])->sort();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $settings = Yii::$app->getModule('admin')->activeModules['article']->settings;
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