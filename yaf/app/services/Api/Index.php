<?php
/**
 * @author JKD
 * @date 2021年08月03日 23:50
 */
namespace app\services\Api;

use Jkd\JkdBaseService;
use Jkd\JkdResponse;
use Task\JkdTask;

class Index extends JkdBaseService
{

    /**
     * Index
     *
     * @return bool
     */
    public function index()
    {
        return JkdResponse::Success($this->JkdRequest ?: 'Hello JkdYaf !');
    }

    public function task()
    {
        JkdTask::dispatch(Test::class, ['name' => '111', 'time' => time()]);
        JkdTask::delay(Test::class, 1500, ['name' => '2222', 'time' => time()]);
        return JkdResponse::Success();
    }

    public function article()
    {
        $articleId = $this->JkdRequest['articleId'] ?? 0;
        $categoryId = $this->JkdRequest['categoryId'] ?? 0;
        $userId = $this->JkdRequest['userId'] ?? 0;
        $title = $this->JkdRequest['title'] ?? time();

        $articleModel = new \ArticleModel();

        if ($this->JkdRequest['type'] == 1) {
            $list = $articleModel->update(['title' => $title], ['id' => $articleId], false, $categoryId);
        } elseif ($this->JkdRequest['type'] == 2) {
            $list = $articleModel->delete(['id' => $articleId], $categoryId);
        } elseif ($this->JkdRequest['type'] == 3) {
            $list = $articleModel->delCache($categoryId);
        } elseif ($this->JkdRequest['type'] == 4) {
            $testModel = new \TestModel();
            $list = $testModel->getCache($userId);
        } elseif ($this->JkdRequest['type'] == 5) {
            $testModel = new \TestModel();
            $list = $testModel->getCache();
        } elseif ($this->JkdRequest['type'] == 6) {
            $list = $articleModel->getCache();
        } else {
            $list = $articleModel->getCache($categoryId, 'id', $articleId);
        }
        return JkdResponse::Success($list);
    }

}
