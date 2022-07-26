<?php

namespace App\Model;

class PostModel extends MainModel
{
    /**
     * get all post
     * @return Array
     */

    public function getAllPost()
    {
        $col_table = [
            'post.id',
            'post.title',
            'post.chapo',
            'post.autor',
            'post.createdAt',
            'post.updatedAt'

        ];
        $join = ['user' => 'user.id=post.id_user'];
        $results = $this->selectData($col_table, $join);
        $custom_array = [];
        foreach ($results as $datas) {
            array_push($custom_array, new PostTable($datas));
        }

        return $custom_array;
    }

    /**
     * get unique post
     * @param String $post_id
     * @return Object
     */

    public function getOnePost(string $post_id)
    {
        $col_table = [
            'post.id',
            'post.title',
            'post.chapo',
            'post.content',
            'post.autor',
            'post.createdAt',
            'post.updatedAt'

        ];
        $join = ['user' => 'user.id=post.id_user'];
        $results = $this->selectOneData($col_table, $join, ['post.id'], [$post_id], null, null);
        return new PostTable($results);
    }


    /**
     * Add post
     * @param Integer $id_user
     * @param String $title
     * @param String $chapo
     * @param String $content
     * @param String $autor
     * @return BOOL
     */

    public function setPost(int $id_user, string $title, string $chapo, string $autor, string $content)
    {
        $date = date("Y-m-d H:i:s");
        $col_table = ['id_user', 'title', 'chapo', 'autor', 'content', 'createdAt', 'updatedAt'];
        $values = array($id_user, $title, $chapo, $autor, $content, $date, $date);
        return $this->insertData($col_table, $values);
    }

    /**
     * Updated post
     * @param String $id
     * @param String $post_title
     * @param String $post_chapo
     * @param String $post_content
     * @param String $post_autor
     * @return BOOL
     */

    public function updatePost(string $id, string $post_title, string $post_chapo, string $post_content, string $post_autor)
    {
        $date = date("Y-m-d H:i:s");
        $col_table = ['title', 'chapo', 'content', 'updatedAt', 'autor'];
        $key = 'id';
        $keyValue = $id;
        $values = array($post_title, $post_chapo, $post_content, $date, $post_autor);

        return $this->updateData($col_table, $values, $key, $keyValue);
    }

    /**
     * Delete post
     * @param String $id
     * @return BOOL
     */

    public function deletePost(string $id)
    {
        $key = 'id';
        $keyValue = $id;
        return $this->deleteData($key, $keyValue);
    }
}
