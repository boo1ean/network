<?php
namespace app\controllers;

use app\models\Book;
use app\models\BookTaking;
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

    public function actionLibraryBookDelete() {

        if (!isset($_POST['id_edit'])) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $libraryForm = new LibraryForm();

        $libraryForm->id_edit  = $_POST['id_edit'];
        $libraryForm->scenario = 'only_id';

        return $libraryForm->libraryBookDelete() ? 'ok' : 'error';
    }

    public function actionLibraryBookEdit() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->getBaseUrl(),
            'status'   => 'ok'
        );
        $link = '';

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

                $storage = Yii::$app->getComponent('storage');
                $link    = $storage->link($libraryForm->resource_id);
                $link = $link ? $link : '';
            }

            $this->layout   = 'block';
            $param          = array('model' => $libraryForm, 'link' => $link);
            $result['html'] = $this->render('libraryBookEdit', $param);
        }

        return json_encode($result);
    }

    public function actionLibraryBookGive() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->getBaseUrl(),
            'status'   => 'ok'
        );

        if (!isset($_POST['book_id']) || !isset($_POST['user_id'])) {
            $result['status'] = 'redirect';
        }

        if ('ok' == $result['status']) {
            $bookTakingModel = new BookTaking();

            $bookTakingModel->book_id  = $_POST['book_id'];
            $bookTakingModel->returned = $_POST['returned'];
            $bookTakingModel->taken    = date('Y-m-d H:i:s', time());
            $bookTakingModel->user_id  = $_POST['user_id'];
            $bookTakingModel->scenario = 'give';

            $result['status'] = $bookTakingModel->giveBook() ? 'ok' : 'error';
            $result['errors'] = $bookTakingModel->errors;
        }

        return json_encode($result);
    }

    public function actionLibraryBookList($status = 'all', $order = 'author-asc', $page = 1) {
        $storage     = Yii::$app->getComponent('storage');
        $libraryForm = new LibraryForm();
        $where       = array();

        switch($status) {
            case 'ask':
                $where['status'] = Book::STATUS_ASK;
                break;
            case 'available':
                $where['status'] = Book::STATUS_AVAILABLE;
                break;
            case 'taken':
                $where['status'] = Book::STATUS_TAKEN;
                break;
        }

        $libraryForm->offset   = $page;
        $libraryForm->order_by = str_replace('-', ' ', $order);
        $books_data = $libraryForm->libraryBookList($where, true);

        if(is_null($books_data['pagination']) && $page != 1) {
            Yii::$app->getResponse()->redirect('/admin/library/'.$status.'/'.$order.'/1');
        }

        $books = array();

        foreach ($books_data['books'] as $key => $book) {
            $books[$key]         = $book->toArray();
            $books[$key]['link'] = $storage->link($book->resource_id);

            switch ($books[$key]['status']) {
                case Book::STATUS_ASK:
                    $books[$key]['status'] = 'ask';;
                    break;
                case Book::STATUS_AVAILABLE:
                    $books[$key]['status'] = 'available';
                    break;
                case Book::STATUS_TAKEN:
                    $books[$key]['status'] = 'taken';
                    $where = array(
                        'book_id'          => $book->id,
                        'status_user_book' => BookTaking::STATUS_TAKEN
                    );
                    $books[$key]['taken_info'] = BookTaking::findOneByParams($where);;
                    break;
            }

            $books[$key]['type'] = $books[$key]['type'] == Book::TYPE_PAPER ? 'Paper' : 'E-book';

        }

        $param = array(
            'books'      => $books,
            'model'      => $libraryForm,
            'order'      => $order,
            'page'       => $page,
            'pagination' => $books_data['pagination'],
            'status'     => $status
        );

        return $this->render('libraryBookList', $param);
    }

    public function actionLibraryBookQueue() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->getBaseUrl(),
            'status'   => 'ok'
        );

        if (!isset($_POST['book_id']) || empty($_POST['book_id']) || ! is_numeric($_POST['book_id'])) {
            $result['status'] = 'redirect';
        }

        if ('ok' == $result['status']) {
            $bookTakingModel          = new BookTaking();
            $bookTakingModel->book_id = $_POST['book_id'];

            $users = $bookTakingModel->getQueueListOfUsers();

            $this->layout = 'block';
            $param = array(
                'model' => $bookTakingModel,
                'users' => $users
            );
            $result['html'] = $this->render('libraryBookQueue', $param);
        }

        return json_encode($result);
    }

    public function actionLibraryBookReturn() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->getBaseUrl(),
            'status'   => 'ok'
        );

        if (!isset($_POST['book_id']) || !isset($_POST['user_id'])) {
            $result['status'] = 'redirect';
        }

        if ('ok' == $result['status']) {
            $bookTakingModel = new BookTaking();

            $bookTakingModel->book_id  = $_POST['book_id'];
            $bookTakingModel->user_id  = $_POST['user_id'];
            $bookTakingModel->scenario = 'return';

            $result['status'] = $bookTakingModel->returnBook() ? 'ok' : 'error';
            $result['errors'] = $bookTakingModel->errors;

            if('ok' == $result['status']) {
                $result['book_status'] = $bookTakingModel->status_user_book == Book::STATUS_ASK ? 'ask' : 'available';
            }
        }

        return json_encode($result);
    }

    public function actionLibraryBookSave() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->getBaseUrl(),
            'status'   => 'ok'
        );

        if (!isset($_POST['id_edit'])) {
            $result['status'] = 'redirect';
        }

        $link = false;
        if ('ok' == $result['status']) {
            $libraryForm = new LibraryForm();

            $libraryForm->author      = $_POST['LibraryForm']['author'];
            $libraryForm->description = $_POST['LibraryForm']['description'];
            $libraryForm->id_edit     = $_POST['id_edit'];
            $libraryForm->resource_id = $_POST['resource_id'];
            $libraryForm->tags        = $_POST['LibraryForm']['tags'];
            $libraryForm->title       = $_POST['LibraryForm']['title'];
            $libraryForm->type        = $_POST['LibraryForm']['type'];

            $libraryForm->libraryBookSave();

            if(!empty($libraryForm->resource_id)) {
                $storage = Yii::$app->getComponent('storage');
                $link    = $storage->link($libraryForm->resource_id);
            }
        }

        $result['status']         = count($libraryForm->errors) > 0 ? 'error' : $result['status'];
        $result['errors']         = $libraryForm->errors;
        $result['book']           = $libraryForm->toArray();
        $result['book']['link']   = $link;
        $result['book']['status'] = $result['book']['status'] == Book::STATUS_AVAILABLE ? 'available' : 'taken';
        $result['book']['type']   = $result['book']['type']   == Book::TYPE_PAPER       ? 'Paper'     : 'E-book';

        return json_encode($result);
    }

    public function actionLibraryBookUpload() {
        $result = array();

        if ($_FILES['ebook']['name'] !== '' && !empty($_FILES['ebook']['tmp_name'])) {
            $book_file = UploadedFile::getInstanceByName('ebook');
            $storage = Yii::$app->getComponent('storage');
            $resource_id = $storage->save($book_file);
            $result['resource_id'] = $resource_id;
            $result['status']      = 'ok';
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

    public function actionUserList($page = 1) {

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
