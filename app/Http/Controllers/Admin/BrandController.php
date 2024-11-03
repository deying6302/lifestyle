<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
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

            $query = Brand::query()->select('id', 'name', 'slug', 'image');

            if ($includeTrashed) {
                $query->onlyTrashed();
            } else {
                $query->whereNull('deleted_at');
            }

            $brands = $query->latest()->get();

            return DataTables::of($brands)
                ->addIndexColumn()
                ->addColumn('action', function ($brand) use ($includeTrashed) {
                    if ($includeTrashed) {
                        return '<button type="button" title="Nút khôi phục" id="' . $brand->id . '" class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm" ><i class="fas fa-trash-restore"></i></button>' .
                            '<button type="button" title="Nút xóa vĩnh viễn" id="' . $brand->id . '" class="forceIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fas fa-trash-alt"></i></button>';
                    } else {
                        return '<button type="button" title="Nút cập nhật" id="' . $brand->id . '" class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm" data-toggle="modal" data-target="#editBrandModel"><i class="fas fa-pencil-alt"></i></button>' .
                            '<button type="button" title="Nút xóa" id="' . $brand->id . '" class="deleteIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fa fa-trash"></i></button>';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.brand.index');
    }

    public function store() // :POST
    {
        DB::beginTransaction();

        try {
            $data = $this->request->only(['name', 'slug']);

            if ($this->request->hasFile('image')) {
                $image_new = $this->uploadImage($this->request->file('image'));
                $data['image'] = $image_new;
            }

            Brand::create($data);
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thêm thương hiệu {$data['name']} thành công"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi thêm thương hiệu']);
        }
    }

    public function edit() // :GET
    {
        $id = $this->request->id;
        $brand = Brand::find($id);
        return response()->json($brand);
    }

    public function update() // :PUT
    {
        $brand_id = $this->request->input('brand_id');

        DB::beginTransaction();

        try {
            $brand = Brand::find($brand_id);
            if (!$brand) {
                return response()->json(['status' => 404, 'message' => 'Brand not found']);
            }

            $data = $this->request->only(['name', 'slug']);

            if ($this->request->hasFile('image')) {
                $image_new = $this->uploadImage($this->request->file('image'));
                $data['image'] = $image_new;

                if (isset($brand->image)) {
                    $this->deleteImage($brand->image);
                }
            } else {
                $data['image'] = $brand->image;
            }

            $brand->update($data);
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Đã cập nhật thương hiệu {$data['name']} thành công"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi cập nhật thương hiệu']);
        }
    }

    public function delete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $brand = Brand::findOrFail($id);
            $brand->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thương hiệu {$brand->name} đã được di chuyển vào thùng rác!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa thương hiệu']);
        }
    }

    public function deleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            $deletedCount = Brand::whereIn('id', $ids)->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} thương hiệu đã được di chuyển vào thùng rác!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa thương hiệu']);
        }
    }

    public function restore() // :POST
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            Brand::where('id', $id)->withTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thương hiệu đã khôi phục thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục thương hiệu']);
        }
    }

    public function restoreAll() // :POST
    {
        DB::beginTransaction();

        try {
            Brand::onlyTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Khôi phục lại tất cả thương hiệu thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục tất cả thương hiệu']);
        }
    }

    public function forceDelete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $brand = Brand::where('id', $id)->withTrashed()->firstOrFail();

            if ($brand->image) {
                $this->deleteImage($brand->image);
            }

            $brand->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thương hiệu đã xóa khỏi cơ sở dữ liệu!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục thương hiệu']);
        }
    }

    public function forceDeleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            // Lấy danh sách các thương hiệu cần xóa
            $brands = Brand::withTrashed()->whereIn('id', $ids)->get();

            foreach ($brands as $brand) {
                if ($brand->image) {
                    $this->deleteImage($brand->image);
                }
            }

            // Xóa các thương hiệu vĩnh viễn
            $deletedCount = Brand::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} thương hiệu đã xóa khỏi cơ sở dữ liệu!!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa thương hiệu']);
        }
    }

    protected function uploadImage($file)
    {
        $image_new = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/brand/'), $image_new);
        return $image_new;
    }

    protected function deleteImage($image)
    {
        $filePath = public_path('uploads/brand/' . $image);

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
