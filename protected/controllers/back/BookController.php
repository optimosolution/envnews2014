<?php

class BookController extends BackEndController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'admin', 'delete'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create', 'update', 'admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Book;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Book'])) {
            $model->attributes = $_POST['Book'];
            if ($model->validate()) {
                $model->created_by = Yii::app()->user->id;
                $model->created_time = new CDbExpression('NOW()');

                $path = Yii::app()->basePath . '/../uploads/books';
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }

                //Picture upload script
                if (@!empty($_FILES['Book']['name']['cover_image'])) {
                    $model->cover_image = $_POST['Book']['cover_image'];

                    if ($model->validate(array('cover_image'))) {
                        $model->cover_image = CUploadedFile::getInstance($model, 'cover_image');
                    } else {
                        $model->cover_image = '';
                    }
                    $model->cover_image->saveAs($path . '/' . time() . '_' . str_replace(' ', '_', strtolower($model->cover_image)));
                    $model->cover_image = time() . '_' . str_replace(' ', '_', strtolower($model->cover_image));
                }

                if ($model->save()) {
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        $previuosFileName = $model->cover_image;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Book'])) {
            $model->attributes = $_POST['Book'];
            if ($model->validate()) {
                $model->modified_by = Yii::app()->user->id;
                $model->modified_time = new CDbExpression('NOW()');

                $path = Yii::app()->basePath . '/../uploads/books';
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                //Picture upload script
                if (@!empty($_FILES['Book']['name']['cover_image'])) {
                    $model->cover_image = $_POST['Book']['cover_image'];

                    if ($model->validate(array('cover_image'))) {
                        $myFile = $path . '/' . $previuosFileName;
                        if ((is_file($myFile)) && (file_exists($myFile))) {
                            unlink($myFile);
                        }
                        $model->cover_image = CUploadedFile::getInstance($model, 'cover_image');
                    } else {
                        $model->cover_image = '';
                    }
                    $model->cover_image->saveAs($path . '/' . time() . '_' . str_replace(' ', '_', strtolower($model->cover_image)));
                    $model->cover_image = time() . '_' . str_replace(' ', '_', strtolower($model->cover_image));
                } else {
                    $model->cover_image = $previuosFileName;
                }

                if ($model->save()) {
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->redirect(array('admin'));
        $dataProvider = new CActiveDataProvider('Book');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Book('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Book'])) {
            $model->attributes = $_GET['Book'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Book the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Book::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Book $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'book-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
