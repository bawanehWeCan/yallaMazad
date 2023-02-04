<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TipRequest;
use App\Models\Tip;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TipCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TipCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Tip::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/tip');
        CRUD::setEntityNameStrings('tip', 'tips');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn(['name' => 'note', 'label'=>'English Note','type'     => 'closure',
        'function' => function(Tip $entry) {
            return $entry->getTranslation('note','en');
        }]);
        $this->crud->addColumn(['name' => 'note_ar', 'label'=>'Arabic Note','type'     => 'closure',
        'function' => function(Tip $entry) {
            return $entry->getTranslation('note','ar');
        }]);
        CRUD::column('created_at');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        $this->crud->addColumn(['name' => 'note', 'label'=>'English Note','type'     => 'closure',
        'function' => function(Tip $entry) {
            return $entry->getTranslation('note','en');
        }]);
        $this->crud->addColumn(['name' => 'note_ar', 'label'=>'Arabic Note','type'     => 'closure',
        'function' => function(Tip $entry) {
            return $entry->getTranslation('note','ar');
        }]);
        CRUD::column('created_at');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(TipRequest::class);

        $this->crud->addField(['name' => 'en', 'type' => 'text','label'=>'English Note', 'store_in'     => 'note','fake'     => true, ]);
        $this->crud->addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Note', 'store_in'     => 'note','fake'     => true, ]);


        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(TipRequest::class);
        $tip = Tip::findOrFail(\Route::current()->parameter('id'));

        $this->crud->addField(['name' => 'en', 'type' => 'text','label'=>'English Note', 'store_in'     => 'note','fake'     => true,'value'=>$tip->getTranslation('note','en') ]);
        $this->crud->addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Note', 'store_in'     => 'note','fake'     => true,'value'=>$tip->getTranslation('note','ar') ]);

    }
}
