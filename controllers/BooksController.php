<?php

namespace app\controllers;

use Yii;
use app\models\Books;
use app\models\BooksImg;
use app\models\BooksSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use app\models\LoginForm;

/**
 * BooksController implements the CRUD actions for Books model.
 */
class BooksController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'delete_images' => ['POST'],
                ],
            ]
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Lists all Books models.
     * @return mixed
     */
    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->user->loginUrl);
        }

        $searchModel = new BooksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('books', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Books model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->user->loginUrl);
        }

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'model_img' => $this->findModel_images($id),
        ]);
    }

    /**
     * Creates a new Books model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->user->loginUrl);
        }

        $model = new Books();
        $model_img = new BooksImg();

        if ($model->load(Yii::$app->request->post())) {
            $model_img->load(Yii::$app->request->post("BooksImg"));
            $model_img->img = UploadedFile::getInstances($model_img, 'img');
            if ($model->validate()) {
                $model->save(false);
                if ($model_img->img) {
                    if (!file_exists((Url::to(Yii::$app->basePath . '/web/uploads/')))) {
                        mkdir(Url::to(Yii::$app->basePath . '/web/uploads/'), 0777, true);
                    }
                    $path = Url::to(Yii::$app->basePath . '/web/uploads/');
                    foreach ($model_img->img as $image) {
                        $images = new BooksImg();
                        $images->book_id = $model->id;
                        $images->img = time() . rand(100, 990) . '.' . $image->extension;
                        if ($images->save()) {
                            $image->saveAs($path . $images->img);
                        }
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', array(
                    'model' => $model,
                    'model_img' => $model_img
        ));
    }

    /**
     * Updates an existing Books model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->user->loginUrl);
        }

        $img_update = new BooksImg();
        $model = $this->findModel($id);
        $model_img = $this->findModel_images($id);
        $olds = $model->getOldAttributes();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $img_update->load(Yii::$app->request->post("BooksImg"));
                $img_update->img = UploadedFile::getInstances($img_update, 'img');
                if ($img_update->img) {
                    if (!file_exists((Url::to(Yii::$app->basePath . '/web/uploads/')))) {
                        mkdir(Url::to(Yii::$app->basePath . '/web/uploads/'), 0777, true);
                    }
                    $path = Url::to(Yii::$app->basePath . '/web/uploads/');
                    foreach ($img_update->img as $image) {
                        $images = new BooksImg();
                        $images->book_id = $id;
                        $images->img = time() . rand(100, 990) . '.' . $image->extension;
                        if ($images->save()) {
                            $image->saveAs($path . $images->img);
                        }
                    }
                }
                $model->save(false);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }




        return $this->render('update', [
                    'model' => $model,
                    'model_img' => $model_img,
                    'img_update' => $img_update,
        ]);
    }

    /**
     * Deletes an existing Books model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->user->loginUrl);
        }

        $image_filename = $this->findModel_images($id);
        foreach ($image_filename as $filename) {
            @unlink(Yii::$app->basePath . '/web/uploads/' . $filename->img);
            $this->findModel_image($filename->id)->delete();
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Deletes Image.
     * @param integer $id
     */
    public function actionDeleteimage($id) {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->user->loginUrl);
        }

        $image_filename = $this->findModel_image($id);
        @unlink(Yii::$app->basePath . '/web/uploads/' . $image_filename->attributes["img"]);
        $this->findModel_image($id)->delete();
        return true;
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * About action.
     *
     */
    public function actionAbout() {
        return $this->render('about');
    }

    /**
     * Finds the Books model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Books the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Books::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds all image for books based on its book primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BooksImg array
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel_images($id) {
        if (($model = BooksImg::find()->where(['book_id' => $id])->all()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the image model based on its primary key value.
     */
    protected function findModel_image($id) {
        if (($model = BooksImg::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
