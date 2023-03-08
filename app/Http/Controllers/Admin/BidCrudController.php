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
        CRUD::addColumn(['name' => 'user', 'label'=>'User','type'     => 'closure',
        'function' => function(Bid $entry) {
            return $entry?->user?->name;
        }]);

        $this->crud->addColumn([
            'name' => 'advertisement_id',
            'label' => 'Advertisement',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->Where('advertisement_id', '=', $searchTerm);
            }
        ]);
        $this->crud->addColumn([
            'name' => 'price',
            'label' => 'Price',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->Where('price', '!=', $searchTerm);
            }
        ]);
        CRUD::column('created_at');
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

}
