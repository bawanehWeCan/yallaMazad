<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AdvertisementRequest;
use App\Models\Advertisement;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AdvertisementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AdvertisementCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
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
        CRUD::setModel(\App\Models\Advertisement::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/advertisement');
        CRUD::setEntityNameStrings('advertisement', 'advertisements');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id')->label('number');
        CRUD::column('name');
        CRUD::column('content');
        CRUD::column('start_price');
        CRUD::column('status');
        CRUD::column('buy_now_price');


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }


    protected function setupShowOperation()
    {
        CRUD::column('id')->label('number');
        CRUD::column('name');
        CRUD::column('content');
        CRUD::column('start_price');
        CRUD::column('end_date');
        CRUD::column('status');
        CRUD::column('buy_now_price');
        CRUD::column('views');
        CRUD::column('number_of_bids');
        CRUD::addColumn(['name' => 'user', 'label'=>'User','type'     => 'closure',
        'function' => function(Advertisement $entry) {
            return $entry?->user?->name;
        }]);
       CRUD::addColumn(['name' => 'category', 'label'=>'Category','type'     => 'closure',
        'function' => function(Advertisement $entry) {
            return $entry?->category?->name;
        }]);
        CRUD::column('start_date');
        CRUD::column('price_one');
        CRUD::column('price_two');
        CRUD::column('price_three');
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

    protected function setupUpdateOperation()
    {
        CRUD::setValidation(AdvertisementRequest::class);

        CRUD::addField([   // select_from_array
            'name'        => 'status',
            'label'       => "Status",
            'type'        => 'select_from_array',
            'options'     => [
                                'pending'=>'pending',
                                'approve'=>'approve',
                                'rejected'=>'rejected',
                            ],
            'allows_null' => false,
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
                        ]);


    }
}
