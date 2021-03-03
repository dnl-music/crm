<?php

namespace app\controllers;

use Yii;
use app\models\Item;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ItemsController implements the CRUD actions for Item model.
 */
class ItemsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if ($action->id == 'add') {
            $this->enableCsrfValidation = false;
        }
        return true;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex() : string
    {
        return $this->render('index', [
            'items' => Item::find()->asArray()->all(),
        ]);
    }

    public function actionAdd()
    {
        $params = Yii::$app->request->post();
        $item = new Item();
        $item->article = $params['article'];
        $item->name = $params['name'];
        $item->remainder = $params['remainder'];
        $item->unit = $params['unit'];
        if($item->validate()) {
            $item->save();
            return json_encode(['success' => true, 'data' => $item->attributes]);
        }

        return json_encode(['success' => false, 'message' => $item->getErrorSummary(true)]);
    }

    public function actionUpdate($id)
    {
        $params = Yii::$app->request->post();
        $item = Item::findOne(['id' => $id]);
        if(!$item) {
            $this->response->statusCode = 404;
            return json_encode(json_decode('{}', true));
        }
        $item->article = $params['article'];
        $item->name = $params['name'];
        $item->remainder = $params['remainder'];
        $item->unit = $params['unit'];
        if($item->validate()) {
            $item->save();
            return json_encode(['success' => true, 'data' => $item->attributes]);
        }

        return json_encode(['success' => false, 'message' => $item->getErrorSummary(true)]);
    }

    /**
     * Displays a single Item model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Item();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->render('update', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Deletes an existing Item model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
