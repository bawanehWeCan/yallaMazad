<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Admin\SliderRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SliderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SliderCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
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
        CRUD::setModel(\App\Models\Slider::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/slider');
        CRUD::setEntityNameStrings('slider', 'sliders');
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
        'function' => function(Slider $entry) {
            return $entry->getTranslation('name','en');
        }]);
        $this->crud->addColumn(['name' => 'name_ar', 'label'=>'Arabic Name','type'     => 'closure',
        'function' => function(Slider $entry) {
            return $entry->getTranslation('name','ar');
        }]);
        CRUD::column('image')->type('image');
        CRUD::column('created_at');


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        $this->crud->addColumn(['name' => 'name', 'label'=>'English Name','type'     => 'closure',
        'function' => function(Slider $entry) {
            return $entry->getTranslation('name','en');
        }]);
        $this->crud->addColumn(['name' => 'name_ar', 'label'=>'Arabic Name','type'     => 'closure',
        'function' => function(Slider $entry) {
            return $entry->getTranslation('name','ar');
        }]);
        CRUD::column('image')->type('image');
        CRUD::column('route_type');
        CRUD::column('route_id');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
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
        CRUD::setValidation(SliderRequest::class);

        $this->crud->addField(['name' => 'en', 'type' => 'text','label'=>'English Name', 'store_in'     => 'name','fake'     => true, ]);
        $this->crud->addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Name', 'store_in'     => 'name','fake'     => true, ]);
        $this->crud->addField([
            'name'      => 'image',
            'label'     => 'Image',
            'type'      => 'upload',
            'upload'=>true,
            'value'=>''
            ]);
        CRUD::field('route_type')->type('text');
        CRUD::field('route_id')->type('number');

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
        $slider = Slider::findOrFail(\Route::current()->parameter('id'));

        CRUD::setValidation(SliderRequest::class);

        $this->crud->addField(['name' => 'en', 'type' => 'text','label'=>'English Name', 'store_in'     => 'name','fake'     => true,'value'=>$slider->getTranslation('name','en')]);
        $this->crud->addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Name', 'store_in'     => 'name','fake'     => true, 'value'=>$slider->getTranslation('name','ar')]);
        $this->crud->addField([
            'name'      => 'image',
            'label'     => 'Image',
            'type'      => 'upload',
            'upload'=>true,
            'value'=>''
            ]);
        CRUD::field('route_type')->type('text');
        CRUD::addField(['name'=>'route_id','type'=>'number','label'=>'Route id']);
    }
    public function insertDataWithValidation($update=null)
    {
        $this->crud->setRequest($this->crud->validateRequest());

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();
        if ($update == 'update') {
            $slider = Slider::findOrFail(\Route::current()->parameter('id'));
            if($request->has('image')&& File::exists($slider->image)){
                unlink($slider->image);
            }
        }
        // Encrypt password if specified.
        $this->crud->setRequest($request);
        $this->crud->unsetValidation(); // Validation has already been run
    }
    protected function setupDeleteOperation()
    {
        $slider = Slider::findOrFail(\Route::current()->parameter('id'));
        if ($slider&& File::exists($slider->image)) {
            unlink($slider->image);
        }
    }

}
