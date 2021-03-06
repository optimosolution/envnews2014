<?php

class UserController extends BackEndController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    protected function beforeAction($action) {
        $access = $this->checkAccess(Yii::app()->controller->id, Yii::app()->controller->action->id);
        if ($access == 1) {
            return true;
        } else {
            Yii::app()->user->setFlash('error', "You are not authorized to perform this action!");
            $this->redirect(array('/site/noaccess'));
        }
    }

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
                'actions' => array('admin', 'create', 'update', 'view', 'edit', 'delete'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'create', 'update', 'view', 'edit', 'delete'),
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

        $model = new User;
        $profile = new UserProfile;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $this->performAjaxValidation(array($model, $profile));

        if (isset($_POST['User'], $_POST['UserProfile'])) {
            $model->attributes = $_POST['User'];
            $profile->attributes = $_POST['UserProfile'];
            if ($model->validate()) {
                $model->back_pwd = $model->password;
                $model->password = SHA1($model->password);
                $model->verifyPassword = SHA1($model->verifyPassword);
                $model->registerDate = new CDbExpression('NOW()');
                $model->activation = md5(microtime());
                $model->group_id = 7;
                $model->status = 1;
                $model->user_type = 0;
                if ($model->save()) {
                    //Save profile
                    $profile->user_id = $model->id;
                    $profile->save();
                    //Send mail to user
                    $message = "<h2>Hello " . $model->name . ",</h2> <br /><br />";
                    $message .= "<b>Welcome and thank you for joining ENVNEWS.ORG.</b> <br /><br />Your username is : " . $model->username . " <br />Your password is : " . $model->username . " <br />Please keep it secret, keep it safe!<br /><br />";
                    $message .= 'We hope you enjoy your stay at ENVNEWS.ORG,<br />if you have any problems, questions, please feel free to contact us at any time.<br /><br />';
                    $message .= "<br /><br />Sincerely, <br />ENVNEWS.ORG Team";
                    $to = $model->email;
                    $subject = 'Welcome to ENVNEWS.ORG';
                    $fromName = Yii::app()->params['adminName'];
                    $fromMail = Yii::app()->params['adminEmail'];
                    User::sendMail($to, $subject, $message, $fromName, $fromMail);

                    Yii::app()->user->setFlash('success', 'User resistration was successfull!.');
                    $this->redirect(array('/user/view', 'id' => $model->id));
                }
            }
        }

        $this->render('create', array(
            'model' => $model,
            'profile' => $profile,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {

        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Saved successfully');
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionEdit($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $model->password = SHA1($model->password);
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Password Changed successfully');
                $this->redirect(array('view', 'id' => $model->id));
            } else {
                Yii::app()->user->setFlash('error', 'Password Changed Unsuccessful!');
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('edit', array(
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
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->redirect(array('admin'));
        $dataProvider = new CActiveDataProvider('User');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {

        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-admin-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
