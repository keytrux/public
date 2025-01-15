<?php

namespace app\controllers;

use app\models\Breed;
use app\models\BreedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BreedController implements the CRUD actions for Breed model.
 */
class BreedController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Breed models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BreedSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Breed model.
     * @param int $id_breed Id Breed
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_breed)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_breed),
        ]);
    }

    /**
     * Creates a new Breed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Breed();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_breed' => $model->id_breed]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Breed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_breed Id Breed
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_breed)
    {
        $model = $this->findModel($id_breed);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_breed' => $model->id_breed]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Breed model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_breed Id Breed
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_breed)
    {
        $this->findModel($id_breed)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Breed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_breed Id Breed
     * @return Breed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_breed)
    {
        if (($model = Breed::findOne(['id_breed' => $id_breed])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
