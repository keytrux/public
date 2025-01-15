<?php

namespace app\controllers;

use app\models\Manufacturer;
use app\models\ManufacturerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ManufacturerController implements the CRUD actions for Manufacturer model.
 */
class ManufacturerController extends Controller
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
     * Lists all Manufacturer models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ManufacturerSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Manufacturer model.
     * @param int $id_manufacturer Id Manufacturer
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_manufacturer)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_manufacturer),
        ]);
    }

    /**
     * Creates a new Manufacturer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Manufacturer();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_manufacturer' => $model->id_manufacturer]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Manufacturer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_manufacturer Id Manufacturer
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_manufacturer)
    {
        $model = $this->findModel($id_manufacturer);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_manufacturer' => $model->id_manufacturer]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Manufacturer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_manufacturer Id Manufacturer
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_manufacturer)
    {
        $this->findModel($id_manufacturer)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Manufacturer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_manufacturer Id Manufacturer
     * @return Manufacturer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_manufacturer)
    {
        if (($model = Manufacturer::findOne(['id_manufacturer' => $id_manufacturer])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
