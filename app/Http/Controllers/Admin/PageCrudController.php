<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PageCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PageCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Page::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/page');
        CRUD::setEntityNameStrings('page', 'pages');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn(['name' => 'title', 'label'=>'English Title','type'     => 'closure',
        'function' => function(Page $entry) {
            return $entry->getTranslation('title','en');
        }]);
        $this->crud->addColumn(['name' => 'title_ar', 'label'=>'Arabic Title','type'     => 'closure',
        'function' => function(Page $entry) {
            return $entry->getTranslation('title','ar');
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
        $this->crud->addColumn(['name' => 'title', 'label'=>'English Title','type'     => 'closure',
        'function' => function(Page $entry) {
            return $entry->getTranslation('title','en');
        }]);
        $this->crud->addColumn(['name' => 'title_ar', 'label'=>'Arabic Title','type'     => 'closure',
        'function' => function(Page $entry) {
            return $entry->getTranslation('title','ar');
        }]);
        $this->crud->addColumn(['name' => 'content', 'label'=>'English Content','type'     => 'closure',
        'function' => function(Page $entry) {
            return $entry->getTranslation('content','en');
        }]);
        $this->crud->addColumn(['name' => 'content_ar', 'label'=>'Arabic Content','type'     => 'closure',
        'function' => function(Page $entry) {
            return $entry->getTranslation('content','ar');
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
        CRUD::setValidation(PageRequest::class);


        $this->crud->addField(['name' => 'en', 'type' => 'text','label'=>'English Title', 'store_in'     => 'title','fake'     => true, ]);
        $this->crud->addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Title', 'store_in'     => 'title','fake'     => true, ]);
        $this->crud->addField(['name' => 'content_en', 'type' => 'textarea','label'=>'English Content']);
        $this->crud->addField(['name' => 'content_ar', 'type' => 'textarea','label'=>'Arabic Content']);
        $this->crud->addField(['name' => 'content', 'type' => 'hidden' ]);

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
        CRUD::setValidation(PageRequest::class);
        $page = Page::findOrFail(\Route::current()->parameter('id'));


        $this->crud->addField(['name' => 'en', 'type' => 'text','label'=>'English Title', 'store_in'     => 'title','fake'     => true,'value'=>$page->getTranslation('title','en') ]);
        $this->crud->addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Title', 'store_in'     => 'title','fake'     => true,'value'=>$page->getTranslation('title','ar') ]);
        $this->crud->addField(['name' => 'content_en', 'type' => 'textarea','label'=>'English Content','value'=>$page->getTranslation('content','en')]);
        $this->crud->addField(['name' => 'content_ar', 'type' => 'textarea','label'=>'Arabic Content','value'=>$page->getTranslation('content','ar')]);
        $this->crud->addField(['name' => 'content', 'type' => 'hidden' ]);
    }


    public function insertDataWithValidation($update=null)
    {
        $this->crud->setRequest($this->crud->validateRequest());

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();

        // Encrypt password if specified.
        $this->setInput($request, 'content', 'content_en', 'content_ar');
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
