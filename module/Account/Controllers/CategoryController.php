<?php

namespace Module\Account\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Account\Models\Category;

class CategoryController extends Controller
{
    use CheckPermission;


    public function index()
    {
        $this->hasAccess("account-categories.index");
        $categories = Category::paginate(30);

        return view('product.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->hasAccess("account-categories.create");


        return view('product.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->hasAccess("account-categories.create");

        $request->validate(['name' => 'required']);
        Category::create($request->all());

        return redirect()->route('categories.index')->with('message', 'Category Create Successful');
    }

    public function edit(Category $category)
    {
        $this->hasAccess("account-categories.edit");


        return view('product.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->hasAccess("account-categories.edit");


        $request->validate(['name' => 'required']);
        $category->update(['name' => $request->name]);

        return redirect()->route('categories.index')->with('message', 'Category Update Successful');
    }


    public function destroy($id)
    {
        $this->hasAccess("account-categories.delete");

        try {
            Category::destroy($id);

            return redirect()->route('categories.index')->with('message', 'Category Successfully Deleted!');
        } catch (\Exception $ex) {
            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
