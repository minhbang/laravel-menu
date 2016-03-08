<?php
namespace Minhbang\Menu;

use Minhbang\Kit\Extensions\Request;

class ItemRequest extends Request
{
    public $trans_prefix = 'menu::common';
    public $rules = [
        'name'  => 'required|max:100',
        'label'  => 'required|max:100',
        'type'   => 'required|max:100',
        'params' => 'required',
    ];

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
