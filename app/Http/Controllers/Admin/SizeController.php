<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SizeController extends Controller
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

            $query = Size::query()->select('id', 'name');

            if ($includeTrashed) {
                $query->onlyTrashed();
            } else {
                $query->whereNull('deleted_at');
            }

            $sizes = $query->latest()->get();

            return DataTables::of($sizes)
                ->addIndexColumn()
                ->addColumn('action', function ($size) use ($includeTrashed) {
                    if ($includeTrashed) {
                        return '<button type="button" title="Nút khôi phục" id="' . $size->id . '" class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm" ><i class="fas fa-trash-restore"></i></button>' .
                            '<button type="button" title="Nút xóa vĩnh viễn" id="' . $size->id . '" class="forceIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fas fa-trash-alt"></i></button>';
                    } else {
                        return '<button type="button" title="Nút cập nhật" id="' . $size->id . '" class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm" data-toggle="modal" data-target="#editSizeModel"><i class="fas fa-pencil-alt"></i></button>' .
                            '<button type="button" title="Nút xóa" id="' . $size->id . '" class="deleteIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fa fa-trash"></i></button>';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.size.index');
    }

    public function store() // :POST
    {
        DB::beginTransaction();

        try {
            $data = $this->request->only(['name']);

            Size::create($data);
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thêm kích cỡ {$data['name']} thành công"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi thêm kích cỡ']);
        }
    }

    public function edit() // :GET
    {
        $id = $this->request->id;
        $size = Size::find($id);
        return response()->json($size);
    }

    public function update() // :PUT
    {
        $size_id = $this->request->input('size_id');

        DB::beginTransaction();

        try {
            $size = Size::find($size_id);

            if (!$size) {
                return response()->json(['status' => 404, 'message' => 'Size not found']);
            }

            $data = $this->request->only(['name']);
            $size->update($data);

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Đã cập nhật kích cỡ {$data['name']} thành công"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi cập nhật kích cỡ']);
        }
    }

    public function delete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $size = Size::findOrFail($id);
            $size->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Kích cỡ {$size->name} đã được di chuyển vào thùng rác!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa kích cỡ']);
        }
    }

    public function deleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            $deletedCount = Size::whereIn('id', $ids)->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} kích cỡ đã được di chuyển vào thùng rác!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa kích cỡ']);
        }
    }

    public function restore() // :POST
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            Size::where('id', $id)->withTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Kích cỡ đã khôi phục thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục kích cỡ']);
        }
    }

    public function restoreAll() // :POST
    {
        DB::beginTransaction();

        try {
            Size::onlyTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Khôi phục lại tất cả kích cỡ thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục tất cả kích cỡ']);
        }
    }

    public function forceDelete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $size = Size::where('id', $id)->withTrashed()->firstOrFail();
            $size->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Kích cỡ đã xóa khỏi cơ sở dữ liệu!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục kích cỡ']);
        }
    }

    public function forceDeleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            // Xóa các màu sắc vĩnh viễn
            $deletedCount = Size::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} kích cỡ đã xóa khỏi cơ sở dữ liệu!!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa kích cỡ']);
        }
    }
}
