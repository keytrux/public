<?php

namespace app\controllers;

use app\models\Color;
use app\models\ColorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ColorController implements the CRUD actions for Color model.
 */
class ColorController extends Controller
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
     * Lists all Color models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ColorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Color model.
     * @param int $id_color Id Color
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_color)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_color),
        ]);
    }

    /**
     * Creates a new Color model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Color();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_color' => $model->id_color]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Color model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_color Id Color
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_color)
    {
        $model = $this->findModel($id_color);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_color' => $model->id_color]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Color model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_color Id Color
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_color)
    {
        $this->findModel($id_color)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Color model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_color Id Color
     * @return Color the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_color)
    {
        if (($model = Color::findOne(['id_color' => $id_color])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
