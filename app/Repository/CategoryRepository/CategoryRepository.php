<?php

namespace App\Repository\CategoryRepository;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryRepository implements CategoryInterface
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function get($filters = array())
    {
        $query = Category::query();

        if(isset($filters['status'])) {
            $query->whereStatus($filters['status']);
        }

        return $query->get();
    }

    public function find($id)
    {
        return $this->category->findOrFail($id);
    }

    public function store($attributes): array
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $category = new Category();
            $category->name = $attributes['name'];
            $category->number_of_question = $attributes['number_of_question'];
            $category->slug = str_replace(' ', '_', trim(strtolower($attributes['name'])));
            $category->status = $attributes['status'];
            $category->save();
            if($category->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Category added successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function update($id, $attributes): array
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $category = $this->find($id);
            $category->name = $attributes['name'];
            $category->number_of_question = $attributes['number_of_question'];
            $category->status = $attributes['status'];
            $category->update();
            if($category->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Category updated successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function destroy($id): array
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $category = $this->find($id);
            if($category->delete()) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Category deleted successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }
}
