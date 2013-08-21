<?php
namespace app\controllers;

use app\models\Book;
use app\models\admin\InviteForm;
use app\models\admin\LibraryForm;
use app\models\admin\MainForm;
use app\models\admin\UserForm;
use Yii;
use yii\web\UploadedFile;


class AdminController extends PjaxController
{
    public function beforeAction($action) {

        $authManager = Yii::$app->getComponent('authManager');
        $user        = Yii::$app->getUser()->getIdentity();

        // Check user on access
        if (!$user || !$authManager->checkAccess($user->id, 'admin')) {
            Yii::$app->getResponse()->redirect('/');
            return false;
        }

        return parent::beforeAction($action);
    }

    public function actionMain() {
        $mainForm = new MainForm();
        $param    = array('model' => $mainForm);

        return $this->render('main', $param);
    }

    public function actionSendInvite() {
        $inviteForm = new InviteForm();
        $param      = array('model' => $inviteForm);

        if ($inviteForm->load($_POST)) {
            $param['message'] = $inviteForm->sendInvite();
        }

        return $this->render('sendInvite', $param);
    }

    /**
     * This is temporary function for simplify application testing
     */
    public function actionSendInviteTest() {
        $inviteForm = new InviteForm();
        $param      = array('model' => $inviteForm);

        if ($inviteForm->load($_POST)) {
            $param['message'] = $inviteForm->sendInviteTest();
        }

        return $this->render('sendInvite', $param);
    }

    public function actionLibraryBookEdit() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->getBaseUrl(),
            'status'   => 'ok'
        );

        if (!isset($_POST['id_edit'])) {
            $result['status'] = 'redirect';
        }

        if ('ok' == $result['status']) {
            $libraryForm           = new LibraryForm();
            $libraryForm->id_edit  = $_POST['id_edit'];
            $libraryForm->scenario = 'only_id';

            if(0 != $libraryForm->id_edit) {
                $libraryForm->libraryBookEdit();
                $tags = '';

                foreach ($libraryForm->tags as $key => $tag) {
                    if(0 == $key) {
                        $tags .= $tag->title;
                    } else {
                        $tags .= ','.$tag->title;
                    }
                }

                $libraryForm->tags = $tags;
            }

            $this->layout = 'block';
            $param = array('model' => $libraryForm);
            $result['html'] = $this->render('libraryBookEdit', $param);
        }

        return json_encode($result);
    }

    public function actionLibraryBookList() {

        $libraryForm = new LibraryForm();

        $books = $libraryForm->libraryBookList();

        foreach ($books as $key => $val) {
            $books[$key]['status'] = $val['status'] == Book::STATUS_AVAILABLE ? 'available' : 'taken';
            $books[$key]['type']   = $val['type']   == Book::TYPE_PAPER       ? 'Paper'     : 'E-book';
        }

        $param = array(
            'model' => $libraryForm,
            'books' => $books
        );

        return $this->render('libraryBookList', $param);
    }

    public function actionLibraryBookSave() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->getBaseUrl(),
            'status'   => 'ok'
        );

        if (!isset($_POST['id_edit'])) {
            $result['status'] = 'redirect';
        }

        if ('ok' == $result['status']) {
            $libraryForm = new LibraryForm();

            $libraryForm->author      = $_POST['LibraryForm']['author'];
            $libraryForm->description = $_POST['LibraryForm']['description'];
            $libraryForm->id_edit     = $_POST['id_edit'];
            $libraryForm->link        = isset($_POST['link_book']) ? $_POST['link_book'] : '';
            $libraryForm->tags        = $_POST['LibraryForm']['tags'];
            $libraryForm->title       = $_POST['LibraryForm']['title'];
            $libraryForm->type        = $_POST['LibraryForm']['type'];

            $libraryForm->libraryBookSave();
        }

        $result['status']  = count($libraryForm->errors) > 0 ? 'error' : $result['status'];
        $result['errors']  = $libraryForm->errors;
        $result['book']    = $libraryForm->toArray();
        $result['book']['status'] = $result['book']['status'] == Book::STATUS_AVAILABLE ? 'available' : 'taken';
        $result['book']['type']   = $result['book']['type']   == Book::TYPE_PAPER       ? 'Paper'     : 'E-book';

        return json_encode($result);
    }

    public function actionLibraryBookUpload() {
        $result = array();

        if ($_FILES['ebook']['name'] !== '' && !empty($_FILES['ebook']['tmp_name'])) {
            $book_file = UploadedFile::getInstanceByName('ebook');
            $storage = Yii::$app->getComponent('storage');
            $link = $storage->save($book_file);
            $result['link']   = $link;
            $result['status'] = 'ok';
        } else {
            $result['status'] = 'error';
        }

        return json_encode($result);
    }

    public function actionUserBlock() {

        if (!isset($_POST['id_edit']) || !isset($_POST['is_block'])) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $userForm = new UserForm();

        $userForm->id_edit  = $_POST['id_edit'];
        $userForm->is_block = $_POST['is_block'];
        $userForm->scenario = 'block';

        $userForm->userBlock();
    }

    public function actionUserDelete() {

        if (!isset($_POST['id_edit'])) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $userForm = new UserForm();

        $userForm->id_edit  = $_POST['id_edit'];
        $userForm->scenario = 'only_id';

        return $userForm->userDelete() ? 'ok' : 'error';
    }

    public function actionUserEdit() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->getBaseUrl(),
            'status'   => 'ok'
        );

        if (!isset($_POST['id_edit'])) {
            $result['status'] = 'redirect';
        }

        if ('ok' == $result['status']) {
            $userForm           = new UserForm();
            $userForm->id_edit  = $_POST['id_edit'];
            $userForm->scenario = 'only_id';

            $userForm->userEdit();

            $this->layout = 'block';
            $param = array('model' => $userForm);
            $result['html'] = $this->render('userEdit', $param);
        }

        return json_encode($result);
    }

    public function actionUserList($page = 0) {

        $userForm = new UserForm();

        $userForm->offset = $page;
        $users_data       = $userForm->userList();
        $users            = array();

        foreach ($users_data['users'] as $key => $user) {
            $users[$key]['avatar'] = $user->getAvatar();
            $users[$key]           = $user;
        }

        $param = array(
            'model'      => $userForm,
            'pagination' => $users_data['pagination'],
            'users'      => $users
        );

        return $this->render('userList', $param);
    }

    public function actionUserSave() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->getBaseUrl(),
            'status'   => 'ok'
        );

        if (!isset($_POST['id_edit'])) {
            $result['status'] = 'redirect';
        }

        if ('ok' == $result['status']) {
            $userForm = new UserForm();

            $userForm->email           = $_POST['UserForm']['email'];
            $userForm->first_name      = $_POST['UserForm']['first_name'];
            $userForm->id_edit         = $_POST['id_edit'];
            $userForm->last_name       = $_POST['UserForm']['last_name'];
            $userForm->password        = $_POST['UserForm']['password'];
            $userForm->repeat_password = $_POST['UserForm']['repeat_password'];

            $userForm->userSave();
        }

        $result['status']  = count($userForm->errors) > 0 ? 'error' : $result['status'];
        $result['errors']  = $userForm->errors;
        $result['user']    = $userForm->toArray();

        return json_encode($result);
    }
}
