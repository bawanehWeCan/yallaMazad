<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Admin\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
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

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn('name');
        $this->crud->addColumn('email');
        $this->crud->addColumn('phone');
        $this->crud->addColumn([ 'name' =>'image','type' => 'image']);
        $this->crud->addColumn( [
            'name' => 'type', // The db column name
            'label' => "Type", // Table column heading
            'type' => 'Text'
          ]);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crud->column('price')->type('number');
         * - $this->crud->addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        $this->crud->addColumn('name');
        $this->crud->addColumn('email');
        $this->crud->addColumn('phone');
        $this->crud->addColumn('number_of_advs')->label('Number Of Advertisements');
        $this->crud->addColumn([ 'name' =>'image','type' => 'image']);
        $this->crud->addColumn( [
            'name' => 'type', // The db column name
            'label' => "Type", // Table column heading
            'type' => 'Text'
          ]);
          $this->crud->addColumn('created_at');
          $this->crud->addColumn('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crud->column('price')->type('number');
         * - $this->crud->addColumn(['name' => 'price', 'type' => 'number']);
         */
    }
    public function store()
    {
        $this->insertDataWithValidation();
        return $this->traitStore();
    }

    public function update()
    {
        $this->insertDataWithValidation('update');
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
        CRUD::setValidation(UserRequest::class);

        CRUD::field('name');
        CRUD::field('email');
        $this->crud->addField(['name'=>'password','type'=>'password','value'=>'']);
        $this->crud->addField(['name'=>'phone','type'=>'text']);
        $this->crud->addField([
            'name'      => 'image',
            'label'     => 'Image',
            'type'      => 'upload',
            'upload'=>true,
            'value'=>''
            ]);
            $this->crud->addField([
            'name'        => 'type',
            'label'       => 'Type',
            'type'        => 'radio',
            'options'     => [
                'user' => "User",
                'admin' => "Admin"
            ],

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
        $this->crud->addField(['name'=>'number_of_advs','type'=>'number','min'=>0]);
    }

    public function insertDataWithValidation($update=null)
    {
        $this->crud->setRequest($this->crud->validateRequest());

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();
        if(!empty($request->password)){
            $password = bcrypt($request->password);
            $request->request->set('password', $password);
        }
        if ($update == 'update') {
            $user = User::findOrFail(\Route::current()->parameter('id'));
            if(empty($request->password)){
                $request->request->remove('password');
            }
            if($request->has('image') && File::exists($user->image)){
                unlink($user->image);
            }
        }
        // Encrypt password if specified.
        $this->crud->setRequest($request);
        $this->crud->unsetValidation(); // Validation has already been run
    }

}
