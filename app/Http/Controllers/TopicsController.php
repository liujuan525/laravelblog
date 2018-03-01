<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Auth;
use App\Handlers\ImageUploadHandler;
use App\Models\User;
use App\Models\Link;

class TopicsController extends Controller
{
    public function __construct()
    {
        // 对除了 index() 和 show() 以外的方法使用 auth 中间件进行认证
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    // 话题首页展示
	public function index(Request $request, Topic $topic, User $user, Link $link)
	{
	    // $request->order 是获取 URI 域名/topics?order=recent 中的 order 参数
        $topics = $topic->withOrder($request->order)->paginate(20);
        $active_users = $user->getActiveUsers();
        $links = $link->getAllCached();
//        dd($active_users);
        return view('topics.index', compact('topics', 'active_users', 'links'));
	}

	// 展示话题内容
    public function show(Request $request, Topic $topic)
    {
        // URL 矫正 -> 强制重定向
        if ( ! empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }

        return view('topics.show', compact('topic'));
    }

    // 新建话题
	public function create(Topic $topic)
	{
	    $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();

        return redirect()->to($topic->link())->with('success', '创建话题成功');
    }

    // 编辑话题
	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	// 更新话题
	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

        return redirect()->to($topic->link())->with('success', '更新话题成功');
	}

	// 删除话题
	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', '删除话题成功!');
	}

	// 图片上传功能
    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'success' => false,
            'msg' => '上传失败!',
            'file_path' => ''
        ];
        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);
            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }
        return false;
    }






}