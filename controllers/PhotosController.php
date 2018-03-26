<?php
namespace yii\easyii2\controllers;

use Yii;
use yii\easyii2\components\ActiveRecord;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\web\Response;

use yii\easyii2\helpers\Image;
use yii\easyii2\components\Controller;
use yii\easyii2\models\Photo;
use yii\easyii2\behaviors\SortableController;

class PhotosController extends Controller
{
    public function behaviors()
    {
        $modelPhoto = ActiveRecord::getModelByName('Photo', 'admin');
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ],
            ],
            [
                'class' => SortableController::className(),
                'model' => $modelPhoto::className(),
            ]
        ];
    }

    public function actionUpload($class, $item_id)
    {
        $success = null;

        $photo = ActiveRecord::getModelByName('Photo', 'admin');
        $photo->class = $class;
        $photo->item_id = $item_id;
        $photo->image = UploadedFile::getInstance($photo, 'image');

        if($photo->image && $photo->validate(['image'])){
            $photo->image = Image::upload($photo->image, 'photos', Photo::PHOTO_MAX_WIDTH);

            if($photo->image){
                if($photo->save()){
                    $success = [
                        'message' => Yii::t('easyii2', 'Photo uploaded'),
                        'photo' => [
                            'id' => $photo->primaryKey,
                            'image' => $photo->image,
                            'thumb' => Image::thumb($photo->image, Photo::PHOTO_THUMB_WIDTH, Photo::PHOTO_THUMB_HEIGHT),
                            'description' => ''
                        ]
                    ];
                }
                else{
                    @unlink(Yii::getAlias('@webroot') . str_replace(Url::base(true), '', $photo->image));
                    $this->error = Yii::t('easyii2', 'Create error. {0}', $photo->formatErrors());
                }
            }
            else{
                $this->error = Yii::t('easyii2', 'File upload error. Check uploads folder for write permissions');
            }
        }
        else{
            $this->error = Yii::t('easyii2', 'File is incorrect');
        }

        return $this->formatResponse($success);
    }

    public function actionDescription($id)
    {
        $photo = ActiveRecord::getModelByName('Photo', 'admin');
        if(($model = $photo::findOne($id)))
        {
            if(Yii::$app->request->post('description'))
            {
                $model->description = Yii::$app->request->post('description');
                if(!$model->update()) {
                    $this->error = Yii::t('easyii2', 'Update error. {0}', $model->formatErrors());
                }
            }
            else{
                $this->error = Yii::t('easyii2', 'Bad response');
            }
        }
        else{
            $this->error = Yii::t('easyii2', 'Not found');
        }

        return $this->formatResponse(Yii::t('easyii2', 'Photo description saved'));
    }

    public function actionImage($id)
    {
        $success = null;
        $model = ActiveRecord::getModelByName('Photo', 'admin');
        if(($photo = $model::findOne($id)))
        {
            $oldImage = $photo->image;

            $photo->image = UploadedFile::getInstance($photo, 'image');

            if($photo->image && $photo->validate(['image'])){
                $photo->image = Image::upload($photo->image, 'photos', Photo::PHOTO_MAX_WIDTH);
                if($photo->image){
                    if($photo->save()){
                        @unlink(Yii::getAlias('@webroot').$oldImage);

                        $success = [
                            'message' => Yii::t('easyii2', 'Photo uploaded'),
                            'photo' => [
                                'image' => $photo->image,
                                'thumb' => Image::thumb($photo->image, Photo::PHOTO_THUMB_WIDTH, Photo::PHOTO_THUMB_HEIGHT)
                            ]
                        ];
                    }
                    else{
                        @unlink(Yii::getAlias('@webroot').$photo->image);

                        $this->error = Yii::t('easyii2', 'Update error. {0}', $photo->formatErrors());
                    }
                }
                else{
                    $this->error = Yii::t('easyii2', 'File upload error. Check uploads folder for write permissions');
                }
            }
            else{
                $this->error = Yii::t('easyii2', 'File is incorrect');
            }

        }
        else{
            $this->error =  Yii::t('easyii2', 'Not found');
        }

        return $this->formatResponse($success);
    }

    public function actionDelete($id)
    {
        $model = ActiveRecord::getModelByName('Photo', 'admin');

        if(($model = $model::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii2', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii2', 'Photo deleted'));
    }

    public function actionUp($id, $class, $item_id)
    {
        return $this->move($id, 'up', ['class' => $class, 'item_id' => $item_id]);
    }

    public function actionDown($id, $class, $item_id)
    {
        return $this->move($id, 'down', ['class' => $class, 'item_id' => $item_id]);
    }
}