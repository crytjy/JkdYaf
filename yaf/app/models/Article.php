<?php

class ArticleModel extends \Jkd\JkdBaseModel
{
    protected $table = 'article';
    protected $fillAble = [];
    protected $selectAble = [];
    protected $isCache = 1;
    protected $cacheKey = 'Article';
    protected $primaryKey = 'id';
    protected $foreignKey = 'category_id';
    protected $isList = 1;
    protected $hasListKey = 1;
}
