<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BidRequest;
use App\Models\Bid;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BidCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BidCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Bid::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/bid');
        CRUD::setEntityNameStrings('bid', 'bids');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('price');
        CRUD::addColumn(['name' => 'user', 'label'=>'User','type'     => 'closure',
        'function' => function(Bid $entry) {
            return $entry?->user?->name;
        }]);
        CRUD::addColumn(['name' => 'advertisement', 'label'=>'Advertisement','type'     => 'closure',
        'function' => function(Bid $entry) {
            return $entry?->advertisement?->name;
        }]);


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }
    protected function setupShowOperation()
    {
        CRUD::column('price');
        CRUD::addColumn(['name' => 'user', 'label'=>'User','type'     => 'closure',
        'function' => function(Bid $entry) {
            return $entry?->user?->name;
        }]);
        CRUD::addColumn(['name' => 'advertisement', 'label'=>'Advertisement','type'     => 'closure',
        'function' => function(Bid $entry) {
            return $entry?->advertisement?->name;
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
        CRUD::setValidation(BidRequest::class);

        CRUD::field('price')->type('text');
        $this->crud->addField(
            [  // Select
                'label'     => "User",
                'type'      => 'select',
                'name'      => 'user_id', // the db column for the foreign key
    
                // optional - manually specify the related model and attribute
                'model'     => "App\Models\Bid", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
    
                'options'   => (function ($query) {
                    return $query->latest()->get();
                }), //  you can use this to filter the results show in the select
            ]);
        $this->crud->addField(
            [  // Select
                'label'     => "Advertisement",
                'type'      => 'select',
                'name'      => 'advertisement_id', // the db column for the foreign key
    
                // optional - manually specify the related model and attribute
                'model'     => "App\Models\Bid", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
    
                'options'   => (function ($query) {
                    return $query->latest()->get();
                }), //  you can use this to filter the results show in the select
            ]);
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
        $this->setupCreateOperation();
    }
}
