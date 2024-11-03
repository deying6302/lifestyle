<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ColorController extends Controller
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

            $query = Color::query()->select('id', 'name', 'code');

            if ($includeTrashed) {
                $query->onlyTrashed();
            } else {
                $query->whereNull('deleted_at');
            }

            $colors = $query->latest()->get();

            return DataTables::of($colors)
                ->addIndexColumn()
                ->addColumn('action', function ($color) use ($includeTrashed) {
                    if ($includeTrashed) {
                        return '<button type="button" title="Nút khôi phục" id="' . $color->id . '" class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm" ><i class="fas fa-trash-restore"></i></button>' .
                            '<button type="button" title="Nút xóa vĩnh viễn" id="' . $color->id . '" class="forceIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fas fa-trash-alt"></i></button>';
                    } else {
                        return '<button type="button" title="Nút cập nhật" id="' . $color->id . '" class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm" data-toggle="modal" data-target="#editColorModel"><i class="fas fa-pencil-alt"></i></button>' .
                            '<button type="button" title="Nút xóa" id="' . $color->id . '" class="deleteIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fa fa-trash"></i></button>';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.color.index');
    }

    public function store() // :POST
    {
        DB::beginTransaction();

        try {
            $data = $this->request->only(['name', 'code']);

            Color::create($data);
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thêm màu sắc {$data['name']} thành công"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi thêm màu sắc']);
        }
    }

    public function edit() // :GET
    {
        $id = $this->request->id;
        $color = Color::find($id);
        return response()->json($color);
    }

    public function update() // :PUT
    {
        $color_id = $this->request->input('color_id');

        DB::beginTransaction();

        try {
            $color = Color::find($color_id);

            if (!$color) {
                return response()->json(['status' => 404, 'message' => 'Color not found']);
            }

            $data = $this->request->only(['name', 'code']);
            $color->update($data);

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Đã cập nhật màu sắc {$data['name']} thành công"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi cập nhật màu sắc']);
        }
    }

    public function delete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $color = Color::findOrFail($id);
            $color->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Màu sắc {$color->name} đã được di chuyển vào thùng rác!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa màu sắc']);
        }
    }

    public function deleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            $deletedCount = Color::whereIn('id', $ids)->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} màu sắc đã được di chuyển vào thùng rác!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa màu sắc']);
        }
    }

    public function restore() // :POST
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            Color::where('id', $id)->withTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Màu sắc đã khôi phục thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục màu sắc']);
        }
    }

    public function restoreAll() // :POST
    {
        DB::beginTransaction();

        try {
            Color::onlyTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Khôi phục lại tất cả màu sắc thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục tất cả màu sắc']);
        }
    }

    public function forceDelete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $color = Color::where('id', $id)->withTrashed()->firstOrFail();
            $color->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Màu sắc đã xóa khỏi cơ sở dữ liệu!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục màu sắc']);
        }
    }

    public function forceDeleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            // Xóa các màu sắc vĩnh viễn
            $deletedCount = Color::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} màu sắc đã xóa khỏi cơ sở dữ liệu!!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa màu sắc']);
        }
    }
}
