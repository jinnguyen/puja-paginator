<?php
namespace Puja\Paginator\Renderer;
/**
 * Class Basic: First Prev 4 5 6 7 8 Next Last
 * @package Puja\Paginator\Renderer
 */
class Basic extends RendererAbstract
{
    protected $limit = 5;
    public function parse()
    {
        return $this->paginator->getFirstPage() .
        $this->paginator->getPrevPage() .
        $this->getPageData() .
        $this->paginator->getNextPage() .
        $this->paginator->getLastPage();
    }

    protected function getPageData()
    {
        $totalPage = $this->paginator->getTotalPage();
        $currentPage = $this->paginator->getCurrentPage();
        $p = array();
        $min = max($currentPage - floor($this->limit / 2), 0);
        if ($currentPage > $totalPage - $this->limit) {
            $min = $totalPage - $this->limit;
        }

        $max = min($currentPage + $this->limit, $totalPage);
        for ($i = $min; $i < $max; $i++) {
            $p[] = $this->paginator->getPageElement($i, true);
        }

        return implode('', array_slice($p, 0, $this->limit));
    }

    
}