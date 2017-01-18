<?php
namespace Puja\Paginator\Renderer;

/**
 * Class Simple: First Prev 1 2 3 4 5 6 7 8 9 10 .. (show all pages) Next Last
 * @package Puja\Paginator\Renderer
 */
class Simple extends RendererAbstract
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
        $p = '';
        for ($i = 0; $i < $this->paginator->getTotalPage(); $i++) {
            $p .= $this->paginator->getPageElement($i, true);
        }

        return $p;
    }


}