<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() // :GET
    {
        if ($this->request->ajax()) {
            $includeTrashed = $this->request->get('include_trashed');

            $query = Category::query()->select('id', 'name', 'slug', 'type', 'image');

            if ($includeTrashed) {
                $query->onlyTrashed();
            } else {
                $query->whereNull('deleted_at');
            }

            $categories = $query->latest()->get();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('action', function ($category) use ($includeTrashed) {
                    $checkbox = '<input type="checkbox" title="Danh mục con" class="btn category-checkbox mr-1" data-category-id="' . $category->id . '" style="width: 27px; height: 27px; margin-top: 1px;">';

                    if ($includeTrashed) {
                        $actionButtons = '<button type="button" title="Nút khôi phục" id="' . $category->id . '" class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm" ><i class="fas fa-trash-restore"></i></button>' .
                            '<button type="button" title="Nút xóa vĩnh viễn" id="' . $category->id . '" class="forceIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm"><i class="fas fa-trash-alt"></i></button>';
                    } else {
                        $actionButtons = '<button type="button" title="Nút cập nhật" id="' . $category->id . '" class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm" data-toggle="modal" data-target="#editCategoryModel"><i class="fas fa-pencil-alt"></i></button>' .
                            '<button type="button" title="Nút xóa" id="' . $category->id . '" class="deleteIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm"><i class="fa fa-trash"></i></button>';
                    }

                    return $checkbox . $actionButtons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.category.index');
    }

    public function store() // :POST
    {
        DB::beginTransaction();

        try {
            $data = $this->request->only(['name', 'slug']);
            $image = $this->request->file('image');

            if ($image) {
                if ($this->request->category_id !== NULL) {
                    $data['image'] = $this->uploadImageWithSubCate($image);
                } else {
                    $data['image'] = $this->uploadImage($image);
                }
            }

            if ($this->request->category_id !== NULL) {
                $data['category_id'] = $this->request->category_id;
                Subcategory::create($data);
            } else {
                $data['type'] = $this->request->type;
                Category::create($data);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thêm danh mục {$data['name']} thành công"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi thêm danh mục']);
        }
    }

    public function edit() // :GET
    {
        $id = $this->request->id;
        $category = Category::find($id);
        return response()->json($category);
    }

    public function update() // :PUT
    {
        $category_id = $this->request->input('category_id');

        DB::beginTransaction();

        try {
            $category = Category::find($category_id);
            if (!$category) {
                return response()->json(['status' => 404, 'message' => 'Danh mục không tồn tại']);
            }

            $data = $this->request->only(['name', 'slug', 'type']);

            if ($this->request->hasFile('image')) {
                $image_new = $this->uploadImage($this->request->file('image'));
                $data['image'] = $image_new;

                if (isset($category->image)) {
                    $this->deleteImage($category->image);
                }
            } else {
                $data['image'] = $category->image;
            }

            $category->update($data);
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Đã cập nhật danh mục {$data['name']} thành công"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi cập nhật danh mục']);
        }
    }

    public function delete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $category = Category::findOrFail($id);
            $category->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Danh mục {$category->name} đã được di chuyển vào thùng rác!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa danh mục']);
        }
    }

    public function deleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            $deletedCount = Category::whereIn('id', $ids)->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} danh mục đã được di chuyển vào thùng rác!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa danh mục']);
        }
    }

    public function restore() // :POST
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            Category::where('id', $id)->withTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Danh mục đã khôi phục thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục danh mục']);
        }
    }

    public function restoreAll() // :POST
    {
        DB::beginTransaction();

        try {
            Category::onlyTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Khôi phục lại tất cả danh mục thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục tất cả danh mục']);
        }
    }

    public function forceDelete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $category = Category::where('id', $id)->withTrashed()->firstOrFail();

            if ($category->image) {
                $this->deleteImage($category->image);
            }

            $category->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Danh mục đã xóa khỏi cơ sở dữ liệu!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa danh mục']);
        }
    }

    public function forceDeleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            // Lấy danh sách các danh mục cần xóa
            $categories = Category::withTrashed()->whereIn('id', $ids)->get();

            foreach ($categories as $category) {
                if ($category->image) {
                    $this->deleteImage($category->image);
                }
            }

            // Xóa các danh mục vĩnh viễn
            $deletedCount = Category::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} danh mục đã xóa khỏi cơ sở dữ liệu!!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa danh mục']);
        }
    }

    public function selectCategory() // :GET
    {
        $categories = Category::whereNull('deleted_at')->latest()->get();
        return response()->json($categories);
    }

    protected function uploadImage($file)
    {
        $image_new = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/category/'), $image_new);
        return $image_new;
    }

    protected function uploadImageWithSubCate($file)
    {
        $image_new = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/subcategory/'), $image_new);
        return $image_new;
    }

    protected function deleteImage($image)
    {
        $filePath = public_path('uploads/category/' . $image);

        if (file_exists($filePath) && is_readable($filePath)) {
            if (unlink($filePath)) {
                usleep(50000);
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}
