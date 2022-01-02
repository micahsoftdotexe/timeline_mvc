<?= yii\helpers\Html::a('Clock In', ['clock-entries/clock-in'], [
    'class' => 'btn btn-lg btn-success',
    'style' => (!app\models\User::findOne(['id' => Yii::$app->user->identity->id])->getCheckClockIn()) ? 'pointer-events: none; color:grey;' : ''
    //'disabled' => !app\models\User::findOne(['id' => Yii::$app->user->identity->id])->checkClockIn
]); ?>
<?= yii\helpers\Html::a('Clock Out', ['clock-entries/clock-out'], [
    'class' => 'btn btn-lg btn-success',
    'style' => (!app\models\User::findOne(['id' => Yii::$app->user->identity->id])->getCheckClockOut()) ? 'pointer-events: none; color:grey;' : ''
    //'disabled' => !app\models\User::findOne(['id' => Yii::$app->user->identity->id])->checkClockOut
]); ?>

