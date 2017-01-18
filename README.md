# puja-paginator
Puja-Paginator is a flexible component for paginating collections of data and presenting that data to users.

Installation
------------

Just run this on the command line:
```
composer require jinnguyen/puja-paginator
```

Usage
-----
```php
include '/path/to/vendor/autoload.php';
use Puja\Paginator\Paginator;
```

Examples:
-----
<strong>Simple</strong>
```php
$paginator = new Paginator(
    '/news/', // url
    100, // total of records
    10 // number of records per page
);
```

The rest of the documentation will assume you have a `$paginator` instance on which you are making calls.

### Adding renderer
```php
$paginator->addRenderer('simple', 'Puja\Paginator\Renderer\Simple'); // Puja\Paginator\Renderer\Simple must be extended of Puja\Paginator\Renderer\RendererAbstract
```

### Set labels
```php
$paginator->setLabels($labes); // default $labels is [First, Prev, Next, Last]
```

### First,Last and Current CSS classes
<pre>
    <ul>
        <li class="pagingClassName first"><a href="/">Home</a></li> // First element
        <li class="pagingClassName "><a href="/page">Page</a></li>
        <li class="pagingClassName current">Subpage</li> // Current Element
        <li class="pagingClassName last">Subpage 2</li> // Last Element
    </ul>
</pre>
The first/last css classes are the class of first/last Breadcrumb element

```php
$paginator->setFirstCssClassName($className);
$paginator->setLastCssClassName($className);
$paginator->setCurrentCssClassName($className);
```

### The Element

The default paging element is `<li class="{CssClassName}">%s{Divider}</li>`. To change it, use the setElement method like so:

```php
$paginator->setElement('<span class="{FirstLastCss}">%s{Divider}</span>');
```

<strong>Note:</strong>
<pre>
"%s" is required for Paginator::$element
{CssClassName}: will be replaced by Paginator::$firstCssClassName/Paginator::$currentCssClassName/Paginator::$lastCssClassName if this element is first/current/last element.
{Divider}: will be replaced by Paginator::$divider
</pre>

### The List Element

The default list element used to wrap the paging, is `<ul>%s</ul>`. To change it, use the setListElement method like so:

```php
$paginator->setListElement('<ol class="ol-paging">%s</ol>');
```

<strong>Note:</strong>
<pre>"%s" is required for Paginator::$listElement</pre>

### Divider
The default divider is `` (empty). This will be replace to placeholder {Divider} in property Paginator::$element. If you'd like to change it to, for example, `/`, you can just do:

```php
$paginator->setDivider('/');
```

### Output

Finally, when you actually want to display your breadcrumbs, all you need to do is call the `render()` method on the instance:

```php
echo $paginator->render('simple');
echo $paginator->render('basic');
echo $paginator->render(); // default is `basic`
```
<strong>Note</strong>
<pre>You can write custom Renderer by yourself. You can check Puja\Paginator\Renderer\Simple as a sample</pre>

<strong>Example</strong>
```php
class CustomRenderer extends \Puja\Paginator\Renderer\RendererAbstract
{
    public function parse()
    {
        $p = '';
        for ($i = 0; $i < $this->paginator->getTotalPage(); $i++) {
            $p .= $this->paginator->getPageElement($i, true);
        }

        return $p;
    }
}

$paginator->addRenderer('custom', 'CustomRenderer');
$paginator->render('custom');
```


Note that by default First/Prev/Next/Last titles are rendered with escaping HTML characters, if you'd like to ignore it just do  like so:

```php
$paginator->setSafeHtml(false);
```