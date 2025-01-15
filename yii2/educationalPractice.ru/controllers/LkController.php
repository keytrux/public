<?php

namespace app\controllers;
use app\models\Problem;
use Yii;
use app\models\User;
use app\models\ProblemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\RegForm;
use yii\helpers\ArrayHelper;
use app\models\Category;
use app\models\ProblemCreateForm;
use yii\web\UploadedFile;

use app\models\Product;
/**
 * UserController implements the CRUD actions for User model.
 */
class LkController extends Controller
{

    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest)
        {
            $this->redirect(['/site/login']);
            return false;
        }
        if (!parent::beforeAction($action))
        {
            return false;
        }
        return true;
    }
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
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProblemSearch();
        $dataProvider = $searchModel->searchForUser($this->request->queryParams,
        Yii::$app->user->identity->id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
   

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->status=='Новая')
        {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', 'Заявка успешно удалена');
        } 
        else
        {
            Yii::$app->session->setFlash('danger', 'Заявка не может быть удалена, так как ее статус был изменен администратором');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Problem::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreate()
    {
        $model = new ProblemCreateForm();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // return $this->redirect(['view', 'id' => $model->id]);

                // $model->photo_before = UploadedFile::getInstance($model, 'photo_before');
                // $newFileName = md5($model->photo_before->baseName .time()).'.'.$model->photo_before->extension;
                // $model->photo_before->saveAs('@app/web/uploads/' . $newFileName);
                // $model->photo_before = $newFileName;
                
                $model->id_user = Yii::$app->user->identity->id;
                $model->save();
                return $this->redirect(['/lk']);
            }
        } else {
            $model->loadDefaultValues();
        }

        $categories = Category::find() -> all();
        $categories = ArrayHelper::map($categories, 'id', 'name');

        $product = Product::find() -> all();
        $product = ArrayHelper::map($product, 'name', 'name');

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
            'product' => $product,
        ]);
    }
}
