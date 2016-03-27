<?php
namespace Minhbang\Menu;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\Kit\Extensions\NestedSetModel;
use Minhbang\Locale\Translatable;
use LocaleManager;
use MenuManager;

/**
 * Class Menu
 *
 * @package Minhbang\Menu
 * @property integer $id
 * @property integer $parent_id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $params
 * @property string $options
 * @property-read mixed $url
 * @property-read \Minhbang\Menu\Menu $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\Menu\Menu[] $children
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereParams($value)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 */
class Menu extends NestedSetModel
{
    use PresentableTrait;
    use Translatable {
        save as traitsave;
    }

    protected $table = 'menus';
    protected $presenter = MenuPresenter::class;
    protected $fillable = ['name', 'label', 'type', 'params', 'options'];
    protected $translatable = ['label'];
    public $timestamps = false;
    /**
     * @var array
     */
    protected $_options;

    /**
     * @param null|string $key
     * @param mixed $default
     *
     * @return mixed;
     */
    public function getOption($key = null, $default = null)
    {
        if (empty($this->options)) {
            return $default;
        } else {
            if (is_null($this->_options)) {
                $this->_options = json_decode($this->options, true);
            }

            return is_null($key) ? $this->_options : array_get($this->_options, $key, $default);
        }
    }

    /**
     * Getter $menu->url
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return MenuManager::buildUrl($this->type, $this->params);
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return \Minhbang\Menu\Menu
     */
    public static function findRootByNameOrCreate($name, $options = [])
    {
        $ext_attributes = ['type' => '#', 'params' => '#', 'options' => json_encode($options)];
        foreach (LocaleManager::all(true) as $locale) {
            $ext_attributes[$locale] = ['label' => $name];
        }

        return parent::firstOrCreate(['name' => $name], $ext_attributes);
    }

    /**
     * Khắc phục tạm thời lỗi không tương thích Translatable và Baum\Node
     *
     * @see https://github.com/dimsav/laravel-translatable/issues/25#issuecomment-47740434
     *
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        $tempTranslations = $this->translations;
        if ($this->exists) {
            if (count($this->getDirty()) > 0) {
                // If $this->exists and dirty, parent::save() has to return true. If not,
                // an error has occurred. Therefore we shouldn't save the translations.
                if (parent::save($options)) {
                    $this->translations = $tempTranslations;

                    return $this->saveTranslations();
                }

                return false;
            } else {
                // If $this->exists and not dirty, parent::save() skips saving and returns
                // false. So we have to save the translations
                $this->translations = $tempTranslations;

                return $this->saveTranslations();
            }
        } elseif (parent::save($options)) {
            // We save the translations only if the instance is saved in the database.
            $this->translations = $tempTranslations;

            return $this->saveTranslations();
        }

        return false;
    }
}
