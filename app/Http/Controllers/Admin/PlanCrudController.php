<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PlanRequest;
use App\Models\Plan;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PlanCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PlanCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Plan::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/plan');
        CRUD::setEntityNameStrings('plan', 'plans');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn(['name' => 'name', 'label'=>'English Name','type'     => 'closure',
        'function' => function(Plan $entry) {
            return $entry->getTranslation('name','en');
        }]);
        $this->crud->addColumn(['name' => 'name_ar', 'label'=>'Arabic Name','type'     => 'closure',
        'function' => function(Plan $entry) {
            return $entry->getTranslation('name','ar');
        }]);
        CRUD::column('price');
        CRUD::column('number_of_auction');
        CRUD::column('time');


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        $this->crud->addColumn(['name' => 'name', 'label'=>'English Name','type'     => 'closure',
        'function' => function(Plan $entry) {
            return $entry->getTranslation('name','en');
        }]);
        $this->crud->addColumn(['name' => 'name_ar', 'label'=>'Arabic Name','type'     => 'closure',
        'function' => function(Plan $entry) {
            return $entry->getTranslation('name','ar');
        }]);
    $this->crud->addColumn(['name' => 'details', 'label'=>'English Details','type'     => 'closure',
        'function' => function(Plan $entry) {
            return $entry->getTranslation('details','en');
        }]);
        $this->crud->addColumn(['name' => 'details_ar', 'label'=>'Arabic Details','type'     => 'closure',
        'function' => function(Plan $entry) {
            return $entry->getTranslation('details','ar');
        }]);
        CRUD::column('price');
        CRUD::column('number_of_auction');
        CRUD::column('time');
        CRUD::column('point_one');
        CRUD::column('point_two');
        CRUD::column('point_three');
        CRUD::column('created_at');
        CRUD::column('updated_at');
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    public function store()
    {
        $this->insertDataWithValidation();
        return $this->traitStore();
    }

    public function update()
    {
        $this->insertDataWithValidation();
        return $this->traitUpdate();
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PlanRequest::class);

        $this->crud->addField(['name' => 'en', 'type' => 'text','label'=>'English Name', 'store_in'     => 'name','fake'     => true, ]);
        $this->crud->addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Name', 'store_in'     => 'name','fake'     => true, ]);
        $this->crud->addField(['name' => 'details_en', 'type' => 'textarea','label'=>'English Details']);
        $this->crud->addField(['name' => 'details_ar', 'type' => 'textarea','label'=>'Arabic Details']);
        $this->crud->addField(['name' => 'details', 'type' => 'hidden' ]);

        CRUD::field('price');
        CRUD::field('number_of_auction');
        CRUD::field('time');
        CRUD::field('point_one');
        CRUD::field('point_two');
        CRUD::field('point_three');

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
        CRUD::setValidation(PlanRequest::class);

        $plan = Plan::findOrFail(\Route::current()->parameter('id'));

        $this->crud->addField(['name' => 'en', 'type' => 'text','label'=>'English Name', 'store_in'     => 'name','fake'     => true,'value'=>$plan->getTranslation('name','en') ]);
        $this->crud->addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Name', 'store_in'     => 'name','fake'     => true,'value'=>$plan->getTranslation('name','en')]);
        $this->crud->addField(['name' => 'details_en', 'type' => 'textarea','label'=>'English Details','value'=>$plan->getTranslation('details','en')]);
        $this->crud->addField(['name' => 'details_ar', 'type' => 'textarea','label'=>'Arabic Details','value'=>$plan->getTranslation('details','en')]);
        $this->crud->addField(['name' => 'details', 'type' => 'hidden' ]);

        CRUD::field('price');
        CRUD::field('number_of_auction');
        CRUD::field('time');
        CRUD::field('point_one');
        CRUD::field('point_two');
        CRUD::field('point_three');


    }

    public function insertDataWithValidation($update=null)
    {
        $this->crud->setRequest($this->crud->validateRequest());

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();

        // Encrypt password if specified.
        $this->setInput($request, 'details', 'details_en', 'details_ar');
        $this->crud->setRequest($request);
        $this->crud->unsetValidation(); // Validation has already been run
    }

    public function setInput($request, $value, $valueEn, $valueAr)
    {
        if ($request->input($valueEn) && $request->input($valueAr)) {
            $request->request->set($value, ['en' => $request->input($valueEn), 'ar' => $request->input($valueAr)]);
            $request->request->remove($valueEn);
            $request->request->remove($valueAr);
        }
    }
}
