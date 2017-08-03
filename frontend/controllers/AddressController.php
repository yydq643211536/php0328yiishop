<?php
namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Locations;

use yii\web\Controller;

class AddressController extends Controller
{
    public $layout = 'address';

    public function actionIndex()
    {
if(!\Yii::$app->user->isGuest){
    $mode=Address::find()->where(['member_id'=>\Yii::$app->user->identity->getId()])->all();
}
if(\Yii::$app->user->isGuest){
    $mode=Address::find()->where(['user_id'=>null])->all();

}
        $model = new Address();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if(!\Yii::$app->user->identity->getId()){
                \Yii::$app->session->setFlash('danger','请先登录成功');
                return $this->redirect(['address/index']);
            }
            $model->member_id=\Yii::$app->user->identity->getId();
            if($model->is_default=1){
                \Yii::$app->db->createCommand()->update('address', ['is_default' => 0])->execute();

            }
           $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->render('index', ['model' =>$model,'mode'=>$mode]);
        }
        return $this->render('index', ['model' =>$model,'mode'=>$mode]);
    }
    public function actionDelete($id){
        Address::findOne(['id'=>$id])->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['address/index']);
    }

    public function actionEdit($id){
         $model=Address::findOne(['id'=>$id]);
        $mode=Address::find()->where(['member_id'=>\Yii::$app->user->identity->getId()])->all();

        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->is_default=1){
                \Yii::$app->db->createCommand()->update('address', ['is_default' => 0])->execute();

            }
            $model->save();
            return $this->redirect(['address/index']);
        }
        return $this->render('index', ['model' =>$model,'mode'=>$mode]);

    }
    public function actionMoren($id){
        \Yii::$app->db->createCommand()->update('address', ['is_default' => 0])->execute();

        $mo=Address::findOne(['id'=>$id]);
            $mo->is_default=1;
        $mo->save();
        \Yii::$app->session->setFlash('success','修改成功');
        return $this->redirect(['address/index']);
    }


}

