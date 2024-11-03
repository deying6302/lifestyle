<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() // :GET
    {
        // Lấy danh sách các danh mục blog
        $categories = Category::where('type', 'blog')->latest()->get();

        // Kiểm tra nếu yêu cầu là Ajax
        if ($this->request->ajax()) {
            $includeTrashed = $this->request->get('include_trashed');

            // Xây dựng truy vấn để lấy dữ liệu từ bảng "blogs" kèm theo mối quan hệ với "categories"
            $query = Blog::with('category')->with('admin')->select('id', 'title', 'slug', 'view_count', 'tags', 'status', 'category_id', 'admin_id', 'image', 'created_at');

            // Nếu yêu cầu bao gồm bài đăng đã bị xóa tạm thời
            if ($includeTrashed) {
                $query->onlyTrashed();
            } else {
                $query->whereNull('deleted_at'); // Chỉ hiển thị bài đăng chưa bị xóa tạm thời
            }

            // Lấy danh sách các bài đăng từ truy vấn
            $blogs = $query->latest()->get()->map(function ($blog) {
                $blog->tags_array = explode(',', $blog->tags);
                return $blog;
            });

            // Trả về dữ liệu cho DataTables
            return DataTables::of($blogs)
                ->addIndexColumn()
                ->addColumn('action', function ($blog) use ($includeTrashed) {
                    if ($includeTrashed) {
                        $actionButtons = '<button type="button" title="Nút khôi phục" id="' . $blog->id . '" class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm mb-1" ><i class="fas fa-trash-restore"></i></button>' .
                            '<button type="button" title="Nút xóa vĩnh viễn" id="' . $blog->id . '" class="forceIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm"><i class="fas fa-trash-alt"></i></button>';
                    } else {
                        $actionButtons = '<button type="button" title="Nút cập nhật" id="' . $blog->id . '" class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm mb-1" data-toggle="modal" data-target="#editBlogModel"><i class="fas fa-pencil-alt"></i></button>' .
                            '<button type="button" title="Nút xóa" id="' . $blog->id . '" class="deleteIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm"><i class="fa fa-trash"></i></button>' .
                            '<button type="button" title="Nút trạng thái" id="' . $blog->id . '" class="statusIcon btn '.($blog->status == 1 ? "btn-success": "btn-dark").' shadow btn-xs sharp btn-sm"><i class="fa '.($blog->status == 1 ? "fa-eye": "fa-eye-slash").'"></i></button>';
                        }

                    return $actionButtons;
                })
                ->addColumn('category_name', function ($blog) {
                    return $blog->category->name;
                })
                ->addColumn('admin_name', function ($blog) {
                    return $blog->admin->full_name;
                })
                ->addColumn('created_at', function ($data) {
                    return $data->created_at;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Trả về view cho trang danh sách bài đăng
        return view('admin.pages.blog.index', compact('categories'));
    }

    public function store() // :POST
    {
        DB::beginTransaction();

        try {
            $data = $this->request->only(['title', 'slug', 'category_id']);
            $image = $this->request->file('image');

            if ($image) {
                $image_new = $this->uploadImage($this->request->file('image'));
                $data['image'] = $image_new;
            }

            $data['description'] = strip_tags($this->request->description, '<p><span><a><strong><em><ul><ol><li>');
            $data['content'] = strip_tags($this->request->content, '<p><span><a><strong><em><ul><ol><li>');
            $data['tags'] = implode(',', $this->request->tags);
            $data['admin_id'] = Auth::guard('admin')->id();

            Blog::create($data);
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thêm bài viết thành công"]);
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi thêm bài viết']);
        }
    }

    public function edit() // :GET
    {
        $id = $this->request->id;
        $blog = Blog::find($id);
        $categories = Category::where('type', 'blog')->latest()->get();
        return response()->json(['blog' => $blog, 'categories' => $categories]);
    }

    public function update() // :PUT
    {
        $blog_id = $this->request->input('blog_id');
        // $cleanedHtmlData = strip_tags($htmlData, '<p><span><a><strong><em><ul><ol><li>');
        DB::beginTransaction();

        try {
            $blog = Blog::find($blog_id);
            if (!$blog) {
                return response()->json(['status' => 404, 'message' => 'Blog không tồn tại']);
            }

            $data = $this->request->only(['title', 'slug', 'category_id']);

            if ($this->request->hasFile('image')) {
                $image_new = $this->uploadImage($this->request->file('image'));
                $data['image'] = $image_new;

                if (isset($blog->image)) {
                    $this->deleteImage($blog->image);
                }
            } else {
                $data['image'] = $blog->image;
            }

            $data['description'] = strip_tags($this->request->description, '<p><span><a><strong><em><ul><ol><li>');
            $data['content'] = strip_tags($this->request->content, '<p><span><a><strong><em><ul><ol><li>');
            $data['tags'] = implode(',', $this->request->tags);
            $data['admin_id'] = Auth::guard('admin')->id();

            $blog->update($data);
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Đã cập nhật bài viết thành công"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi cập nhật bài viêt']);
        }
    }

    public function delete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $blog = Blog::findOrFail($id);
            $blog->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Bài viết đã được di chuyển vào thùng rác!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa bài viết']);
        }
    }

    public function deleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            $deletedCount = Blog::whereIn('id', $ids)->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} bài viết đã được di chuyển vào thùng rác!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa bài viết']);
        }
    }

    public function restore() // :POST
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            Blog::where('id', $id)->withTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Bài viết đã khôi phục thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục bài viết']);
        }
    }

    public function restoreAll() // :POST
    {
        DB::beginTransaction();

        try {
            Blog::onlyTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Khôi phục lại tất cả bài viết thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục tất cả bài viết']);
        }
    }

    public function forceDelete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $blog = Blog::where('id', $id)->withTrashed()->firstOrFail();

            if ($blog->image) {
                $this->deleteImage($blog->image);
            }

            $blog->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Bài viết đã xóa khỏi cơ sở dữ liệu!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa bài viết']);
        }
    }

    public function forceDeleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            // Lấy danh sách các danh mục cần xóa
            $blogs = Blog::withTrashed()->whereIn('id', $ids)->get();

            foreach ($blogs as $blog) {
                if ($blog->image) {
                    $this->deleteImage($blog->image);
                }
            }

            // Xóa các danh mục vĩnh viễn
            $deletedCount = Blog::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} bài viết đã xóa khỏi cơ sở dữ liệu!!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa bài viết']);
        }
    }

    public function changeStatus() // :POST
    {
        $id = $this->request->id;
        $newStatus = $this->request->input('new_status');

        DB::beginTransaction();

        try {
            $blog = Blog::findOrFail($id);
            $blog->status = $newStatus;
            $blog->save();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Đã cập nhật trạng thái bài viết thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi cập nhật trạng thái bài viết']);
        }
    }

    protected function uploadImage($file)
    {
        $image_new = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/blog/'), $image_new);
        return $image_new;
    }

    protected function deleteImage($image)
    {
        $filePath = public_path('uploads/blog/' . $image);

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
