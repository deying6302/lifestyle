<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() // :GET
    {
        $categories = Category::where('type', 'product')->get();
        $brands = Brand::all();
        $colors = Color::all();
        $sizes = Size::all();

        if ($this->request->ajax()) {
            $includeTrashed = $this->request->get('include_trashed');

            $query = Product::query()
                ->with(['subcategory', 'brand', 'colors', 'sizes'])
                ->select('id', 'name', 'quantity', 'sold_quantity', 'SKU', 'tags', 'price', 'discount', 'image', 'subcategory_id', 'brand_id');

            if ($includeTrashed) {
                $query->onlyTrashed();
            } else {
                $query->whereNull('deleted_at');
            }

            $products = $query->latest()->get();

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('subcategory_name', function ($product) {
                    return $product->subcategory ? $product->subcategory->name : '';
                })
                ->addColumn('brand_name', function ($product) {
                    return $product->brand ? $product->brand->name : '';
                })
                ->addColumn('colors', function ($product) {
                    // Hiển thị tên màu sắc liên kết với sản phẩm
                    return $product->colors->pluck('code')->implode(', ');
                })
                ->addColumn('sizes', function ($product) {
                    // Hiển thị tên màu sắc liên kết với sản phẩm
                    return $product->sizes->pluck('name')->implode(', ');
                })
                ->addColumn('action', function ($product) use ($includeTrashed) {
                    $checkbox = '<input type="checkbox" title="Thư viện ảnh" class="btn gallery-checkbox mt-1 mb-1 mr-1" data-product_id="' . $product->id . '" style="width: 27px; height: 27px">';

                    if ($includeTrashed) {
                        $actionButtons = '<button type="button" title="Nút khôi phục" id="' . $product->id . '" class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 mb-1 btn-sm" ><i class="fas fa-trash-restore"></i></button>' .
                            '<button type="button" title="Nút xóa vĩnh viễn" id="' . $product->id . '" class="forceIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fas fa-trash-alt"></i></button>';
                    } else {
                        $actionButtons = '<button type="button" title="Nút cập nhật" id="' . $product->id . '" class="editIcon btn btn-primary shadow btn-xs sharp mr-1 mb-1 btn-sm" data-toggle="modal" data-target="#editProductModel"><i class="fas fa-pencil-alt"></i></button>' .
                            '<button type="button" title="Nút xóa" id="' . $product->id . '" class="deleteIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fa fa-trash"></i></button>';
                    }

                    return $checkbox . $actionButtons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.product.index', compact('categories', 'brands', 'colors', 'sizes'));
    }

    public function store() // :POST
    {
        DB::beginTransaction();

        try {
            $data = $this->request->only(['name', 'slug', 'quantity', 'SKU', 'description', 'content', 'tags', 'price', 'discount', 'subcategory_id', 'brand_id']);

            // Xử lý hình ảnh nếu có
            if ($this->request->hasFile('image')) {
                $image = $this->request->file('image');
                $data['image'] = $this->uploadImage($image);
            }

            // Tao sản phẩm
            $product = Product::create($data);

            // If an image was uploaded, copy to gallery and save to gallery table
            if ($this->request->hasFile('image')) {
                $this->copyToGallery($data['image']);
                $this->saveToGallery($product->id, $data['image']);
            }

            // Attach selected colors to the product
            if ($this->request->has('color_ids')) {
                $product->colors()->sync($this->request->input('color_ids'));
            }

            // Attach selected size to the product
            if ($this->request->has('size_ids')) {
                $product->sizes()->sync($this->request->input('size_ids'));
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thêm sản phẩm {$product->name} thành công"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi thêm sản phẩm']);
        }
    }

    public function edit() // :GET
    {
        $id = $this->request->id;

        $product = Product::with(['colors', 'sizes'])->findOrFail($id);
        $categories = Category::where('type', 'product')->get();
        $brands = Brand::all();
        $colors = Color::all();
        $sizes = Size::all();

        return response()->json([
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
            'colors' => $colors,
            'sizes' => $sizes
        ]);
    }

    public function update() // :PUT
    {
        $product_id = $this->request->input('product_id');

        DB::beginTransaction();

        try {
            $product = Product::find($product_id);

            if (!$product) {
                return response()->json(['status' => 404, 'message' => 'Sản phẩm không tồn tại']);
            }

            $data = $this->request->only(['name', 'slug', 'quantity', 'SKU', 'description', 'content', 'tags', 'price', 'discount', 'subcategory_id', 'brand_id']);

            if ($this->request->hasFile('image')) {
                $newImage = $this->uploadImage($this->request->file('image'));

                if (isset($product->image)) {
                    // Xóa ảnh cũ trong bảng gallery
                    $this->deleteFromGallery($product->id, $product->image);

                    // Xóa ảnh cũ trong bảng product
                    $this->deleteImage($product->image);
                }

                $data['image'] = $newImage;
            } else {
                $data['image'] = $product->image;
            }

            // Update the product
            $product->update($data);

            // If an image was uploaded, copy to gallery and save to gallery table
            if ($this->request->hasFile('image')) {
                $this->copyToGallery($data['image']);
                $this->saveToGallery($product->id, $data['image']);
            }

            // Sync colors with the product
            if ($this->request->has('color_ids')) {
                $product->colors()->sync($this->request->input('color_ids'));
            } else {
                $product->colors()->detach();
            }

            // Sync sizes with the product
            if ($this->request->has('size_ids')) {
                $product->sizes()->sync($this->request->input('size_ids'));
            } else {
                $product->sizes()->detach();
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Đã cập nhật sản phẩm {$data['name']} thành công"]);
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi cập nhật sản phẩm']);
        }
    }

    public function delete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $product = Product::findOrFail($id);
            $product->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Sản phẩm {$product->name} đã được di chuyển vào thùng rác!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa sản phẩm']);
        }
    }

    public function deleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            $deletedCount = Product::whereIn('id', $ids)->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} sản phẩm đã được di chuyển vào thùng rác!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa sản phẩm']);
        }
    }

    public function restore() // :POST
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            Product::where('id', $id)->withTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Sản phẩm đã khôi phục thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục sản phẩm']);
        }
    }

    public function restoreAll() // :POST
    {
        DB::beginTransaction();

        try {
            Product::onlyTrashed()->restore();
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
            $product = Product::where('id', $id)->withTrashed()->firstOrFail();

            if ($product->image) {
                $this->deleteImage($product->image);
            }

            $product->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Sản phẩm đã xóa khỏi cơ sở dữ liệu!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa sản phẩm']);
        }
    }

    public function forceDeleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            // Lấy danh sách các sản phẩm cần xóa
            $products = Product::withTrashed()->whereIn('id', $ids)->get();

            foreach ($products as $product) {
                if ($product->image) {
                    $this->deleteImage($product->image);
                }
            }

            // Xóa các sản phẩm vĩnh viễn
            $deletedCount = Product::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} sản phẩm đã xóa khỏi cơ sở dữ liệu!!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa sản phẩm']);
        }
    }

    protected function uploadImage($file)
    {
        $image_new = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/product/'), $image_new);
        return $image_new;
    }

    protected function copyToGallery($imageFileName)
    {
        $imagePath = public_path("uploads/product/{$imageFileName}");
        $galleryPath = public_path("uploads/gallery/");


        if (!File::exists($galleryPath)) {
            File::makeDirectory($galleryPath, 0755, true);
        }

        File::copy($imagePath, $galleryPath . $imageFileName);
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

    /**
     * Xóa ảnh cũ khỏi bảng product.
     */
    protected function deleteImage($image)
    {
        $filePath = public_path("uploads/product/{$image}");

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

    /**
     * Xóa ảnh cũ khỏi bảng gallery.
     */
    protected function deleteFromGallery($productId, $imagePath)
    {
        // Xóa record ảnh cũ trong bảng gallery dựa vào product_id và image path
        Gallery::where('product_id', $productId)->where('image', $imagePath)->delete();

        // Xóa ảnh khỏi thư mục uploads/gallery
        $galleryImagePath = public_path("uploads/gallery/{$imagePath}");
        if (file_exists($galleryImagePath)) {
            unlink($galleryImagePath);
        }
    }

    /**
     * Hàm tải danh mục con
     */
    public function loadSubcategory() // :GET
    {
        $categoryId = $this->request->id;

        // Lấy danh mục con theo ID của danh mục cha
        $subcategories = Subcategory::where('category_id', $categoryId)->get();

        // Trả về danh mục con dưới dạng JSON
        return response()->json(['subcategories' => $subcategories]);
    }
}
