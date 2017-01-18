<?php
namespace Puja\Paginator;

class Paginator
{

    protected $labels = array('First', 'Prev', 'Next', 'Last');
    protected $element = '<li class="{CssClassName}">%s{Divider}</li>';
    protected $listElement = '<ul>%s</ul>';
    protected $firstCssClassName;
    protected $lastCssClassName;
    protected $currentCssClassName;
    protected $divider;
    protected $safeHtml;
    protected $url;
    protected $currentPage;
    protected $totalPage;
    protected $urlHasQuery;
    protected $renderers;

    public function __construct($url, $totalRecords, $recordPerPage)
    {
        $this->url = rtrim($url, '/') . '/';
        $this->totalPage = 0;
        if ($recordPerPage) {
            $this->totalPage = ceil($totalRecords / $recordPerPage);
        }

        if (strpos($url, '?') !== false) {
            $this->urlHasQuery = true;
        }
        $this->safeHtml = true;
        $this->currentPage = 0;
        if (!empty($_REQUEST['page'])) {
            $this->currentPage = (int)$_REQUEST['page'];
        }
        $this->renderers = array();
        $this->addRenderer('simple', 'Puja\Paginator\Renderer\Simple');
        $this->addRenderer('basic', 'Puja\Paginator\Renderer\Basic');
    }

    public function setSafeHtml($safeHtml)
    {
        $this->safeHtml = $safeHtml;
        return $this;
    }

    public function addRenderer($key, $rendererClass)
    {
        if (array_key_exists($key, $this->renderers)) {
            throw new Exception($key . ':' . $rendererClass . '  already exists!');
        }

        $this->renderers[$key] = $rendererClass;
        return $this;
    }

    public function getLabels()
    {
        return $this->labels;
    }

    public function setLabels($labels)
    {
        if (!is_array($labels) || count($labels) != 4) {
            throw new Exception('$labels must be array with 4 elements, example: [First, Prev, Next, Last]');
        }

        return $this;
    }

    public function getElement()
    {
        return $this->element;
    }

    public function setElement($element)
    {
        if (strpos($element, '%s') === false) {
            throw new Exception('Element mus have %s');
        }

        $this->element = $element;
        return $this;
    }

    public function getListElement()
    {
        return $this->listElement;
    }

    public function setListElement($listElement)
    {
        if (strpos($listElement, '%s') === false) {
            throw new Exception('Element mus have %s');
        }
        $this->listElement = $listElement;
        return $this;
    }

    public function getDivider()
    {
        return $this->divider;
    }

    public function setDivider($divider)
    {
        $this->divider = $divider;
        return $this;
    }

    public function getCurrentCssClassName()
    {
        return $this->currentCssClassName;
    }

    public function setCurrentCssClassName($currentCssClassName)
    {
        $this->currentCssClassName = $currentCssClassName;
        return $this;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function getFirstCssClassName()
    {
        return $this->firstCssClassName;
    }

    public function setFirstCssClassName($firstCssClassName)
    {
        $this->firstCssClassName = $firstCssClassName;
        return $this;
    }

    public function getLastCssClassName()
    {
        return $this->lastCssClassName;
    }

    public function setLastCssClassName($lastCssClassName)
    {
        $this->lastCssClassName = $lastCssClassName;
        return $this;
    }

    public function getTotalPage()
    {
        return $this->totalPage;
    }

    public function render($type = 'basic')
    {
        if (empty($this->renderers[$type])) {
            throw new Exception('Render type: ' . $type . ' doesnt exist!');
        }

        $renderClass = $this->renderers[$type];
        if (!class_exists($renderClass)) {
            throw new Exception($renderClass . ' doesnt exist!');
        }

        $renderer = new $renderClass($this);
        if (!($renderer instanceof Renderer\RendererAbstract)) {
            throw new Exception($renderClass . ' must be extended of Puja\Paginator\Renderer\RendererAbstract');
        }

        return $renderer->parse();
    }

    public function getFirstPage()
    {
        if ($this->currentPage == 0) {
            return null;
        }
        return $this->getPageElement(0, true, $this->labels[0]);
    }

    public function getPrevPage()
    {
        if ($this->currentPage == 0) {
            return null;
        }

        return $this->getPageElement($this->currentPage - 1, true, $this->labels[1]);
    }


    public function getLastPage()
    {
        if ($this->currentPage >= $this->totalPage - 1) {
            return null;
        }
        return $this->getPageElement($this->totalPage - 1, true, $this->labels[3]);
    }

    public function getNextPage()
    {
        if ($this->currentPage >= $this->totalPage - 1) {
            return null;
        }

        return $this->getPageElement($this->currentPage + 1, true, $this->labels[2]);
    }

    public function getPageElement($page, $checkCurrent = true, $label = null)
    {
        $anchor = $this->getAnchor($page, $label, $checkCurrent);
        if (empty($this->element)) {
            return $anchor;
        }

        $cssClassName = '';
        if ($page == 0) {
            $cssClassName = $this->firstCssClassName;
        }

        if ($page == $this->totalPage - 1) {
            $cssClassName = $this->lastCssClassName;
        }

        if ($page == $this->currentPage) {
            $cssClassName .= ' ' . $this->currentCssClassName;
        }

        $divider = $this->divider;
        if ($page == $this->totalPage - 1) {
            $divider = '';
        }

        return str_replace(array('%s', '{CssClassName}', '{Divider}'), array($anchor, $cssClassName, $divider), $this->element);
    }

    protected function getAnchor($page, $label = null, $checkCurrent = true)
    {
        if (empty($label)) {
            $label = $page + 1;
        } elseif ($this->safeHtml) {
            $label = htmlentities($label);
        }

        if ($checkCurrent && $page == $this->currentPage) {
            return $page + 1;
        }

        $url = $this->url;
        if ($page) {
            if ($this->urlHasQuery) {
                $url .= '&page=' . $page;
            } else {
                $url .= '?page=' . $page;
            }
        }

        return '<a href="' . $url . '">' . $label . '</a>';
    }
}

?>