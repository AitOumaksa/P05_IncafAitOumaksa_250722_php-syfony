<?php

namespace App\Controller;

use App\Model\ConnectDB;
use App\Model\PDOModel;
use App\Model\PostModel;
use App\Model\commentModel;
use App\Routes\HttpRequest;

class PostController extends MainController
{
    /**
     * get all posts and diplay
     * @return void
     */

    public function getPosts()
    {
        $postModel = new PostModel(new PDOModel(ConnectDB::getPDO()));
        $posts = $postModel->getAllPost();
        $this->view('posts.twig', compact('posts'));
    }

    /**
     * get one posts with his comments and display
     * @param String $post_id
     * @return void
     */

    public function getOnePost(string $post_id)
    {
        $postModel = new PostModel(new PDOModel(ConnectDB::getPDO()));
        $commentModel = new CommentController();
        $comments = $commentModel->getComments($post_id);
        $one_post = $postModel->getOnePost($post_id);
        $this->view('onePost.twig', compact('one_post', 'comments'));
    }


    /**
     * check the input and add post
     * @param Object $requestForPost
     * @return void
     */

    public function addPost(HttpRequest $requestForPost)
    {
        $id_user = $this->session->getUserVar('id');
        $is_admin = $this->session->getUserVar('is_admin');
        $data = $requestForPost->valueForm();

        try {
            $this->verifyInputMessage($data['title']);
            $this->verifyInputMessage($data['chapo']);
            $this->verifyInputMessage($data['content']);
            $this->verifyInputName($data['autor']);

            if ($is_admin === 'Admin') {
                $postModel = new PostModel(new PDOModel(ConnectDB::getPDO()));
                $setPost = $postModel->setPost($id_user, $data['title'], $data['chapo'], $data['autor'], $data['content']);
                if ($setPost) {
                    echo json_encode(array("success" => true));
                }
            } else {
                throw new \Exception(' you can\'t add a post, you are not a admin');
            }
        } catch (\Exception $e) {
            echo json_encode(array("error" => $e->getMessage()));
        }
    }

    /**
     * check the input and add updated a post
     * @param Object $requestForPost
     * @param String $id
     * @return void
     */

    public function updatePost(HttpRequest $requestForPost, string $id)
    {
        $data = $requestForPost->valueForm();
        $is_admin = $this->session->getUserVar('is_admin');

        try {
            $this->verifyInputMessage($data['title']);
            $this->verifyInputMessage($data['chapo']);
            $this->verifyInputMessage($data['content']);
            $this->verifyInputName($data['autor']);
            if ($is_admin === 'Admin') {
                $postModel = new PostModel(new PDOModel(ConnectDB::getPDO()));
                $updatePost = $postModel->updatePost($id, $data['title'], $data['chapo'], $data['content'], $data['autor']);

                if ($updatePost) {
                    echo json_encode(array("success" => true));
                }
            } else {
                throw new \Exception(' you can\'t update a post, you are not a admin');
            }
        } catch (\Exception $e) {
            echo json_encode(array("error" => $e->getMessage()));
        }
    }

    /**
     * Delete post
     * @param String $id
     * @return void
     */

    public function deletePost(string $id)
    {
        $is_admin = $this->session->getUserVar('is_admin');

        try {
            if ($is_admin === 'Admin') {
                $postModel = new PostModel(new PDOModel(ConnectDB::getPDO()));
                $deletePost = $postModel->deletePost($id);
                if ($deletePost) {
                    echo json_encode(array("success" => true));
                }
            } else {
                throw new \Exception(' you can\'t remove a post, you are not a admin');
            }
        } catch (\Exception $e) {
            echo json_encode(array("error" => $e->getMessage()));
        }
    }
}
