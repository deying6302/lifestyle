<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
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

            $query = Coupon::query()
                ->select('id', 'code', 'discount_type', 'discount_value', 'threshold', 'quantity', 'start_date', 'end_date', 'is_active');

            if ($includeTrashed) {
                $query->onlyTrashed();
            } else {
                $query->whereNull('deleted_at');
            }

            $coupons = $query->latest()->get();

            return DataTables::of($coupons)
                ->addIndexColumn()
                ->addColumn('action', function ($coupon) use ($includeTrashed) {
                    if ($includeTrashed) {
                        return '<button type="button" title="'. __('admin.action.button_restore') .'" id="' . $coupon->id . '" class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm" ><i class="fas fa-trash-restore"></i></button>' .
                            '<button type="button" title="'. __('admin.action.button_delete_permanently') .'" id="' . $coupon->id . '" class="forceIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fas fa-trash-alt"></i></button>';
                    } else {
                        return '<button type="button" title="'. __('admin.action.button_update') .'" id="' . $coupon->id . '" class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm" data-toggle="modal" data-target="#editCouponModel"><i class="fas fa-pencil-alt"></i></button>' .
                            '<button type="button" title="'. __('admin.action.button_delete') .'" id="' . $coupon->id . '" class="deleteIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fa fa-trash"></i></button>';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.coupon.index');
    }

    public function store() // :POST
    {
        DB::beginTransaction();

        try {
            Coupon::create($this->request->all());
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thêm mã giảm giá thành công"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi thêm mã giảm giá']);
        }
    }

    public function edit() // :GET
    {
        $id = $this->request->id;
        $coupon = Coupon::find($id);
        return response()->json($coupon);
    }

    public function update() // :PUT
    {
        $coupon_id = $this->request->input('coupon_id');

        DB::beginTransaction();

        try {
            $coupon = Coupon::find($coupon_id);

            if (!$coupon) {
                return response()->json(['status' => 404, 'message' => 'Coupon not found']);
            }

            $data = $this->request->only(['code', 'discount_type', 'discount_value', 'threshold', 'description', 'quantity', 'start_date', 'end_date']);

            $coupon->update($data);
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Đã cập nhật mã giảm giá thành công"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi cập nhật mã giảm giá']);
        }
    }

    public function delete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Mã giảm giá {$coupon->name} đã được di chuyển vào thùng rác!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa mã giảm giá']);
        }
    }

    public function deleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            $deletedCount = Coupon::whereIn('id', $ids)->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} mã giảm giá đã được di chuyển vào thùng rác!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa mã giảm giá']);
        }
    }

    public function restore() // :POST
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            Coupon::where('id', $id)->withTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Mã giảm giá đã khôi phục thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục mã giảm giá']);
        }
    }

    public function restoreAll() // :POST
    {
        DB::beginTransaction();

        try {
            Coupon::onlyTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Khôi phục lại tất cả mã giảm giá thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục tất cả mã giảm giá']);
        }
    }

    public function forceDelete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $coupon = Coupon::where('id', $id)->withTrashed()->firstOrFail();
            $coupon->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Mã giảm giá đã xóa khỏi cơ sở dữ liệu!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục mã giảm giá']);
        }
    }

    public function forceDeleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            // Xóa các mã giảm giá vĩnh viễn
            $deletedCount = Coupon::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} mã giảm giá đã xóa khỏi cơ sở dữ liệu!!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa mã giảm giá']);
        }
    }
}
