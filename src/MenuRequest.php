<?php
namespace Minhbang\Menu;

use Minhbang\Locale\TranslatableRequest;

/**
 * Class MenuRequest
 *
 * @package Minhbang\Menu
 */
class MenuRequest extends TranslatableRequest
{
    public $trans_prefix = 'menu::common';
    public $rules = [
        'name'   => 'required|max:100',
        'label'  => 'required|max:100',
        'type'   => 'required|max:100',
        'params' => 'required',
    ];
    public $translatable = ['label'];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }

}
