<?php

namespace app\controllers;

use app\models\Country;
use app\models\CountrySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class CountryController extends Controller
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
     * Lists all Country models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CountrySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Country model.
     * @param int $id_country Id Country
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_country)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_country),
        ]);
    }

    /**
     * Creates a new Country model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Country();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_country' => $model->id_country]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Country model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_country Id Country
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_country)
    {
        $model = $this->findModel($id_country);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_country' => $model->id_country]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Country model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_country Id Country
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_country)
    {
        $this->findModel($id_country)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Country model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_country Id Country
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_country)
    {
        if (($model = Country::findOne(['id_country' => $id_country])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
