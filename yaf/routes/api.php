<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
use Facades\JkdRoute;

JkdRoute::get('index', [IndexController::class, 'index'])->limit(1, 200)->middleware('Test1');
