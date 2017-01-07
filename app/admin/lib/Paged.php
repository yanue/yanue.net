<?php
namespace App\Admin\Lib;

use Library\Core\View;

class Paged {
    public $view = null;

    public function __construct(View $view){
        $this->view=$view;
    }
    // 公用分页显示
    public function showPage($page, $count, $limit = 14, $range = 10) {
        $total = ceil($count/$limit);
        // 总页数
        $page = $page > $total ? $total : $page;
        $page = $page <= 0 ? 1 : $page;
        // 上一页
        if ($page > 1) {
            $this->view->previous = $page - 1;
            $this->view->first = 1;
        }
        // 下一页
        if ($total > $page) {
            $this->view->next = $page + 1;
            $this->view->last = $total;
        }
        $this->view->current = $page;
        // $range表示显示条数的一半-1
        if ($page <= $range) {
            if ($total > $range * 2) {
                $pagesInRange = $this->getPagesInRange ( 1, $range * 2 );
            } else {
                $pagesInRange = $this->getPagesInRange ( 1, $total );
            }

        } elseif ($total - $page < $range) {
            $pagesInRange = $this->getPagesInRange ( $total - $range * 2, $total );
        } else {
            $pagesInRange = $this->getPagesInRange ( $page - $range, $page + $range );
        }

        $this->view->pagesInRange = $pagesInRange;
        $this->view->total = $total;
        $this->view->page = $page;
        $this->view->perpage = $limit;
        $this->view->pageCount = $count;
    }

    // 设置页码功能数组处理
    private function getPagesInRange($lowerBound, $upperBound) {
        $pages = array ();
        for($pageNumber = $lowerBound; $pageNumber <= $upperBound; $pageNumber ++) {
            $pages [$pageNumber] = $pageNumber;
        }
        return $pages;
    }
}