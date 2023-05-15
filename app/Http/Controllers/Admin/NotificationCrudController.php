<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notification;
use App\Traits\NotificationTrait;
use App\Http\Requests\Admin\NotificationRequest;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class NotificationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class NotificationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation{
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation{
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use NotificationTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Notification::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/notification');
        CRUD::setEntityNameStrings('notification', 'notifications');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('content');
        CRUD::column('user_id');

        $this->crud->addColumn(['name' => 'view', 'label'=>'View','type'     => 'closure',
        'function' => function(Notification $entry) {
            if ($entry->is_read==0) {
                return "Unread";
            } else if($entry->is_read==1){
                return "Read";
            }
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
        CRUD::column('content');
        CRUD::column('user_id');
        $this->crud->addColumn(['name' => 'view', 'label'=>'View','type'     => 'closure',
        'function' => function(Notification $entry) {
            if ($entry->is_read==0) {
                return "Unread";
            } else if($entry->is_read==1){
                return "Read";
            }
        }]);
        CRUD::column('type');
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
        if ($request->user_id==null) {
            $request['user_id']=0;
        }
        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest($request));
        $this->data['entry'] = $this->crud->entry = $item;
        if ($this->data['entry']->user_id==0) {
            $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();

            $this->send($this->data['entry']->content, "Notification", $FcmToken, $many = true);
        }else{
            $this->addNewNotificationSend($this->data['entry']->content,$this->data['entry']->user->device_token);        // show a success message
        }
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
        if ($request->user_id==null) {
            $request['user_id']=0;
        }
        // update the row in the db
        $item = $this->crud->update(
            $request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest($request)
        );

        $this->data['entry'] = $this->crud->entry = $item;
        if ($this->data['entry']->user_id==0) {
            $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();

            $this->send($this->data['entry']->content, "Notification", $FcmToken, $many = true);
        }else{
            $this->addNewNotificationSend($this->data['entry']->content,$this->data['entry']->user->device_token);        // show a success message
        }
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
        CRUD::setValidation(NotificationRequest::class);

        CRUD::field('content')->type('textarea');
        $this->crud->addField(
            [  // Select
                'label'     => "User",
                'type'      => 'select',
                'name'      => 'user_id', // the db column for the foreign key

                // optional - manually specify the related model and attribute
                'model'     => "App\Models\User", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'allows_null' => true,

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
