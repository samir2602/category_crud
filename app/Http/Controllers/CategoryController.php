<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ChildCategory;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Request()->request->add(['Pagetitle' => "Category", 'btntext' => 'Add Category', 'btnclass' => 'btn btn-primary', 'btnurl' => route('category.create')]);
        if ($request->ajax()) {
            $data = Category::get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return "<a href='".route('category.edit',$row->id)."' class='btn btn-primary btn-sm'>Edit</a> <a href='javascript:void(0)' data-url='" . route('category.destroy', $row->id) . "' class='btn btn-danger btn-sm delete-item'>Delete</a>";
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        Request()->request->add(['Pagetitle' => "Add Category", 'btntext' => 'Back', 'btnclass' => 'btn btn-danger', 'btnurl' => route('category.index')]);
        $data['category_list'] = Category::get();        
        return view('category.add_form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post = $request->except('parent_category');
        $request->validate(['name'=> 'required|unique:categories']);
        $parent_cate = $request->input('parent_category');
        $category = Category::create($post);

        if($category && $parent_cate){
            $data = [];
            foreach ($parent_cate as $value) {
                $data[] = ['category_id' => $category->id, 'parent_id' => $value];
            }
            ChildCategory::insert($data);
        }
        return redirect()->to('category')->with('success', 'Category Added Succesfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        Request()->request->add(['Pagetitle' => "Add Category", 'btntext' => 'Back', 'btnclass' => 'btn btn-danger', 'btnurl' => route('category.index')]);
        $data['category'] = $category;
        $data['category_list'] = Category::where('id','!=',$category->id)->get();
        $data['parent_ids'] = Category::select('child_categories.parent_id')
            ->join('child_categories', 'categories.id', '=', 'child_categories.category_id')
            ->where(['child_categories.category_id' => $category->id])
            ->pluck('child_categories.parent_id')
            ->toArray();
        return view('category.edit_form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $post = $request->except('parent_category');
        $request->validate(['name' => 'required|unique:categories,name,'.$category->id]);
        $parent_cate = $request->input('parent_category');
        $category->update($post);
        if($category && $parent_cate){
            ChildCategory::where('category_id', $category->id)->delete();
            $data = [];
            foreach ($parent_cate as $value) {
                $data[] = ['category_id' => $category->id, 'parent_id' => $value];
            }
            ChildCategory::insert($data);
        }
        return redirect()->to('category')->with('success', 'Category Updated Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        Category::destroy($category->id);
        return response()->json(['status' => true]) ;
    }
}
