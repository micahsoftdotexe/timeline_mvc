<?php

namespace app\controllers;

use app\models\ClockEntries;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\helpers\Url;

class ClockEntriesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['clock-in', 'clock-out'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionClockIn()
    {
        if (\app\models\User::findOne(['id' => Yii::$app->user->identity->id])->checkClockIn) {
            $model = new \app\models\ClockEntries();
            $model->user_id = Yii::$app->user->identity->id;
            $model->clock_in_time = date('Y-m-d H:i:s');
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', 'Could not clock in');
            }
            Yii::$app->user->logout();
            return $this->goHome();
        }
    }

    public function actionClockOut()
    {
        if (\app\models\User::findOne(['id' => Yii::$app->user->identity->id])->checkClockOut) {
            $model = ClockEntries::findOne(['id'=> ClockEntries::find()->where(['user_id' =>Yii::$app->user->identity->id])->max('id')]);
            //$model->user_id = Yii::$app->user->identity->id;
            Yii::debug($model->clock_out_time, 'dev');
            $model->clock_out_time = date('Y-m-d H:i:s');
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', 'Could not clock out');
            }
            Yii::$app->user->logout();
            return $this->goHome();
        }
    }
}