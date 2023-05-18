<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SubscriptionRequest;
use App\Models\Subscription;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SubscriptionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubscriptionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Subscription::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/subscription');
        CRUD::setEntityNameStrings('subscription', 'subscriptions');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('start_date');
        CRUD::column('end_date');
        CRUD::addColumn(['name' => 'user', 'label'=>'User','type'     => 'closure',
        'function' => function(Subscription $entry) {
            return $entry?->user?->name;
        }]);
                CRUD::addColumn(['name' => 'plan', 'label'=>'Plan','type'     => 'closure',
        'function' => function(Subscription $entry) {
            return $entry?->plan?->name;
        }]);


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupshowOperation()
    {
        CRUD::column('start_date');
        CRUD::column('end_date');
        CRUD::addColumn(['name' => 'user', 'label'=>'User','type'     => 'closure',
        'function' => function(Subscription $entry) {
            return $entry?->user?->name;
        }]);
                CRUD::addColumn(['name' => 'plan', 'label'=>'Plan','type'     => 'closure',
        'function' => function(Subscription $entry) {
            return $entry?->plan?->name;
        }]);
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
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest($request));
        $this->data['entry'] = $this->crud->entry = $item;
        $this->data['entry']->user()->update([
            'number_of_advs'=>(int)$this->data['entry']->plan->number_of_auction
        ]);
        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // update the row in the db
        $item = $this->crud->update(
            $request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest($request)
        );
        $this->data['entry'] = $this->crud->entry = $item;
        dd((int)$this->data['entry']->plan->number_of_auction);
        $this->data['entry']->user()->update([
            'number_of_advs'=>(int)$this->data['entry']->plan->number_of_auction
        ]);
        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SubscriptionRequest::class);

        CRUD::field('start_date')->type('date');
        CRUD::field('end_date')->type('date');
        $this->crud->addField(
            [  // Select
                'label'     => "User",
                'type'      => 'select',
                'name'      => 'user_id', // the db column for the foreign key

                // optional - manually specify the related model and attribute
                'model'     => "App\Models\User", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user

                'options'   => (function ($query) {
                    return $query->latest()->get();
                }), //  you can use this to filter the results show in the select
            ]);
        $this->crud->addField(
            [  // Select
                'label'     => "Plan",
                'type'      => 'select',
                'name'      => 'plan_id', // the db column for the foreign key

                // optional - manually specify the related model and attribute
                'model'     => "App\Models\Plan", // related model
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
        CRUD::setValidation(SubscriptionRequest::class);
        $subscription = Subscription::findOrFail(\Route::current()->parameter('id'));
        CRUD::addField(['name'=>'start_date','type'=>'date','value'=> date("Y-m-d",strtotime($subscription->start_date))]);
        CRUD::addField(['name'=>'end_date','type'=>'date','value'=> date("Y-m-d",strtotime($subscription->end_date))]);
        $this->crud->addField(
            [  // Select
                'label'     => "User",
                'type'      => 'select',
                'name'      => 'user_id', // the db column for the foreign key

                // optional - manually specify the related model and attribute
                'model'     => "App\Models\User", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user

                'options'   => (function ($query) {
                    return $query->latest()->get();
                }), //  you can use this to filter the results show in the select
            ]);
        $this->crud->addField(
            [  // Select
                'label'     => "Plan",
                'type'      => 'select',
                'name'      => 'plan_id', // the db column for the foreign key

                // optional - manually specify the related model and attribute
                'model'     => "App\Models\Plan", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user

                'options'   => (function ($query) {
                    return $query->latest()->get();
                }), //  you can use this to filter the results show in the select
            ]);
    }
}

