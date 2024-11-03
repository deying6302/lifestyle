<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() // :GET
    {
        $includeTrashed = $this->request->get('include_trashed');

        $product_id = $this->request->id;

        $query = Gallery::query()->where('product_id', $product_id);

        if ($includeTrashed) {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        $galleries = $query->latest()->get();

        $output = '';

        if ($galleries->count() > 0) {
            $output .= '
            <div class="table-responsive">
                <div class="table-content">
                    <div class="project-table">
                        <form>
                            ' . csrf_field() . '

                            <table class="display table dataTable table-striped table-bordered no-footer table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check custom-checkbox">
                                                <input type="checkbox" class="form-check-input" id="checkAllGallery"
                                                    required="">
                                                <label class="form-check-label" for="checkAllGallery"></label>
                                            </div>
                                        </th>
                                        <th>' . __("admin.product.gallery_path") . '</th>
                                        <th>' . __("admin.product.product_image") . '</th>
                                        <th>' . __("admin.product.product_name") . '</th>
                                        <th>' . __("admin.common.action") . '</th>
                                    </tr>
                                </thead>
                                <tbody>';
            foreach ($galleries as $gallery) {
                $output .= '
                                            <tr>
                                                <td>
                                                    <div class="form-check custom-checkbox">
                                                        <input type="checkbox" class="form-check-input checkbox_gallery_ids" name="gallery_ids" required="" value="' . $gallery->id . '">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                                </td>
                                                <td>' . $gallery->name . '</td>
                                                <td>
                                                    <img src="' . url('uploads/gallery/' . $gallery->image) . '" class="img-thumbnail img-custom" width="100">
                                                </td>
                                                <td>' . $gallery->product->name . '</td>
                                                <td>';
                if ($includeTrashed) {
                    $output .= '<button type="button" title="Nút khôi phục" id="' . $gallery->id . '" class="restoreGallery btn btn-danger shadow btn-xs sharp mr-1 btn-sm" ><i class="fas fa-trash-restore"></i></button>';
                    $output .= '<button type="button" title="Nút xóa vĩnh viễn" id="' . $gallery->id . '" class="forceGallery btn btn-danger shadow btn-xs sharp btn-sm"><i class="fas fa-trash-alt"></i></button>';
                } else {
                    $output .= '<button type="button" title="Nút xóa" id="' . $gallery->id . '" class="deleteGallery btn btn-danger shadow btn-xs sharp btn-sm"><i class="fa fa-trash"></i></button>';
                }
                $output .= '
                                                </td>
                                            </tr>';
            }
            $output .= '
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            ';
        } else {
            $output .= '<div style="text-align: center;">Không có chứa danh sách bản ghi</div>';
        }

        echo $output;
    }

    public function store() // :POST
    {
        DB::beginTransaction();

        try {
            $productId = $this->request->input('product_id');

            if ($this->request->hasFile('images')) {
                $images = $this->request->file('images');

                foreach ($images as $image) {
                    $imageName = $this->uploadImage($image);
                    $this->saveToGallery($productId, $imageName);
                }
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thêm thư viện ảnh thành công"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi thêm thư viện ảnh']);
        }
    }

    public function delete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $gallery = Gallery::findOrFail($id);
            $gallery->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thư viện ảnh đã được di chuyển vào thùng rác!"]);
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
            $deletedCount = Gallery::whereIn('id', $ids)->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} thư viện ảnh đã được di chuyển vào thùng rác!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa thư viện ảnh']);
        }
    }

    public function restore() // :POST
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            Gallery::where('id', $id)->withTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thư viện ảnh đã khôi phục thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục thư viện ảnh']);
        }
    }

    public function restoreAll() // :POST
    {
        DB::beginTransaction();

        try {
            Gallery::onlyTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Khôi phục lại tất cả thư viện ảnh thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục tất cả thư viện ảnh']);
        }
    }

    public function forceDelete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $gallery = Gallery::where('id', $id)->withTrashed()->firstOrFail();

            if ($gallery->image) {
                $this->deleteImage($gallery->image);
            }

            $gallery->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thư viện ảnh đã xóa khỏi cơ sở dữ liệu!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa thư viện ảnh']);
        }
    }

    public function forceDeleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            // Lấy danh sách các thư viện ảnh cần xóa
            $galleries = Gallery::withTrashed()->whereIn('id', $ids)->get();

            foreach ($galleries as $gallery) {
                if ($gallery->image) {
                    $this->deleteImage($gallery->image);
                }
            }

            // Xóa các thư viện ảnh vĩnh viễn
            $deletedCount = Gallery::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} thư viện ảnh đã xóa khỏi cơ sở dữ liệu!!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa thư viện ảnh']);
        }
    }

    protected function uploadImage($file)
    {
        $image_new = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/gallery/'), $image_new);
        return $image_new;
    }

    protected function saveToGallery($productId, $imageFileName)
    {
        // Assuming you have a Gallery model and table to save the gallery images
        Gallery::create([
            'name' => pathinfo($imageFileName, PATHINFO_FILENAME),
            'image' => $imageFileName,
            'product_id' => $productId
        ]);
    }

    protected function deleteImage($image)
    {
        $filePath = public_path('uploads/gallery/' . $image);

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
