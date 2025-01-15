<?php

namespace app\controllers;

use app\models\Product;
use app\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\ArrayHelper;
use app\models\Category;
use app\models\Manufacturer;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
     * Lists all Product models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param int $id_product Id Product
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_product)
    {

        $manufacturer=Manufacturer::find()->all();
        $manufacturer = ArrayHelper::map($manufacturer, 'id_manufacturer', 'name_manufacturer');

        $categories=Category::find()->all();
        $categories = ArrayHelper::map($categories, 'id_category', 'name_category');

        return $this->render('view', [
            'model' => $this->findModel($id_product),
            'categories' => $categories,
            'manufacturer' => $manufacturer,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_product' => $model->id_product]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $manufacturer=Manufacturer::find()->all();
        $manufacturer = ArrayHelper::map($manufacturer, 'id_manufacturer', 'name_manufacturer');

        $categories=Category::find()->all();
        $categories = ArrayHelper::map($categories, 'id_category', 'name_category');

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
            'manufacturer' => $manufacturer,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_product Id Product
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_product)
    {
        $model = $this->findModel($id_product);

        $manufacturer=Manufacturer::find()->all();
        $manufacturer = ArrayHelper::map($manufacturer, 'id_manufacturer', 'name_manufacturer');

        $categories=Category::find()->all();
        $categories = ArrayHelper::map($categories, 'id_category', 'name_category');

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_product' => $model->id_product]);
        }

        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
            'manufacturer' => $manufacturer,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_product Id Product
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_product)
    {
        $this->findModel($id_product)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_product Id Product
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_product)
    {
        if (($model = Product::findOne(['id_product' => $id_product])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
