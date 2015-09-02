<?php
namespace Minhbang\LaravelMenu;

use Laracasts\Presenter\PresentableTrait;
use Baum\Node;

/**
 * App\MenuItem
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $label
 * @property string $type
 * @property string $params
 * @property-read mixed $url
 * @property-read \Minhbang\LaravelMenu\MenuItem $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\LaravelMenu\MenuItem[] $children
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereParams($value)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 */
class MenuItem extends Node
{
    use PresentableTrait;

    const TYPE_URL = 'url';
    const TYPE_CATEGORY = 'category';
    const TYPE_PRODUCT = 'product';
    const TYPE_PAGE = 'page';

    protected $table = 'menus';
    protected $presenter = 'Minhbang\LaravelMenu\MenuItemPresenter';
    protected $fillable = ['label', 'type'];
    public $timestamps = false;

    // attribute phụ, không có trong DB
    public $url;
    public $category_id;
    public $product_id;
    public $page_id;

    /**
     * Khởi tạo các attribute phụ theo params
     */
    public function initAttributes()
    {
        if ($this->exists) {
            switch ($this->type) {
                case static::TYPE_URL:
                    return $this->url = $this->params;
                    break;
                case static::TYPE_CATEGORY:
                    $this->category_id = $this->params;
                    break;
                case static::TYPE_PRODUCT:
                    $this->product_id = $this->params;
                    break;
                case static::TYPE_PAGE:
                    $this->page_id = $this->params;
                    break;
            }
        }
    }

    /**
     * Getter $menu->url
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return app('menu')->getUrl($this->type, $this->params);
    }

    /**
     * @return string
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public static function html()
    {
        return (new static())->present()->html;
    }

    /**
     * @param array $inputs
     */
    public function fillParams($inputs)
    {
        switch ($this->type) {
            case static::TYPE_URL:
                $this->params = $inputs['url'];
                break;
            case static::TYPE_CATEGORY:
                $this->params = $inputs['category_id'];
                break;
            case static::TYPE_PRODUCT:
                $this->params = $inputs['product_id'];
                break;
            case static::TYPE_PAGE:
                $this->params = $inputs['page_id'];
                break;
            default:
                $this->params = '#';
        }
    }
}
