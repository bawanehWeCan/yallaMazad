<?php

namespace App\Http\Controllers\Admin;

use App\Models\Image;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Admin\ImageRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ImageCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ImageCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation{
        store as traitStore;
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
        CRUD::setModel(\App\Models\Image::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/image');
        CRUD::setEntityNameStrings('image', 'images');
    }



    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->orderBy('advertisement_id');
        CRUD::addColumn(['name' => 'advertisement', 'label'=>'Ad name','type'     => 'closure',
        'function' => function(Image $entry) {
            return $entry?->advertisement?->name;
        }]);
        CRUD::addColumn(['name'=>'image','type'=>'image']);

        CRUD::column('created_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        CRUD::addColumn(['name'=>'image','type'=>'image']);
        CRUD::addColumn(['name' => 'advertisement', 'label'=>'Ad name','type'     => 'closure',
        'function' => function(Image $entry) {
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
        CRUD::setValidation(ImageRequest::class);

        $this->crud->addField([   // Upload
            'name'      => 'images',
            'label'     => 'Images',
            'type'      => 'upload_multiple',
            'upload'    => true,
        ]);


        $this->crud->addField([   // Upload
           'name'      => 'image',
           'type'      => 'hidden',
        ]);
        $this->crud->addField(
            [  // Select
                'label'     => "Advertisement",
                'type'      => 'select',
                'name'      => 'advertisement_id', // the db column for the foreign key

                // optional - manually specify the related model and attribute
                'model'     => "App\Models\Advertisement", // related model
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

    public function store()
    {
        $this->crud->setRequest($this->crud->validateRequest());

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();
        if ($request->has('images') ) {
            foreach ($request->images as $k => $image) {


                $request->request->set('image',$image);

                $this->crud->setRequest($request);
                $this->crud->unsetValidation(); // Validation has already been run
                if (count($request->images)-1 === $k) {
                    return $this->traitStore();
                }
                $this->traitStore();
            }
        }


    }
    protected function setupDeleteOperation()
    {
        $image = Image::findOrFail(\Route::current()->parameter('id'));
        if ($image && File::exists($image->image)) {
            unlink($image->image);
        }
    }
}
