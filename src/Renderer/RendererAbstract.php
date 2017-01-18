<?php
namespace Puja\Paginator\Renderer;

use Puja\Paginator\Paginator;

abstract class RendererAbstract
{
    protected $paginator;

    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    abstract public function parse();
}