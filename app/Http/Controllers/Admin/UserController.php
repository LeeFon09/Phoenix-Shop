<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Support\Facades\Mail;
use App\Libs\Pages\Page;
use hightman\xunsearch\lib;

class UserController extends Controller
{
    /**
     * 会员列表页的显示
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $level = DB::table('user_level')->get();
        $count = DB::table('users')->count();
        $pageObj = new Page($count, $request, "admin/User/index");//分页类实例化
        $data = DB::table('users')->orderBy('user_id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        return view('admin/User/index', compact('data', 'level', 'pageObj'));
    }

    /**
     * 添加会员页面的显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('Admin.User.add_user');
    }

    /**
     * 处理添加会员的操作
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $input = $request->except('_token');
        $input['reg_time'] = time();
        $password = Hash::make($input['password']);
        $data = [
            'nickname' => $input['nickname'],
            'password' => $password,
            'email' => $input['email'],
            'sex' => $input['sex'],
            'qq' => $input['qq'],
            'mobile' => $input['mobile'],
            'reg_time' => $input['reg_time']
        ];
        $res = DB::table('users')->insert($data);
        if ($res) {
            return redirect('admin/User/index');
        } else {
            return back();
        }
    }

    /**
     * 编辑会员信息页面的显示
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userEdit($id)
    {
        $data = DB::table('users')->where('user_id', $id)->first();
        return view('Admin.User.detail', compact('data'));
    }

    /**
     * 处理编辑会员信息的操作
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function userHandle(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            $user_id = $input['user_id'];
            if (!empty($input['password'])) {
                $password = Hash::make($input['password']);
                $data = [
                    'nickname' => $input['nickname'],
                    'email' => $input['email'],
                    'password' => $password,
                    'sex' => $input['sex'],
                    'qq' => $input['qq'],
                    'mobile' => $input['mobile'],
                    'is_lock' => $input['is_lock'],
                    'is_distribut' => $input['is_distribut'],
                ];
            } else {
                $data = [
                    'nickname' => $input['nickname'],
                    'email' => $input['email'],
                    'sex' => $input['sex'],
                    'qq' => $input['qq'],
                    'mobile' => $input['mobile'],
                    'is_lock' => $input['is_lock'],
                    'is_distribut' => $input['is_distribut'],
                ];
            }
            $res = DB::table('users')->where('user_id', $user_id)->update($data);
            if ($res) {
                return redirect('admin/User/index');
            } else {
                return back();
            }
        }
    }

    /**
     * 删除会员信息的操作
     *
     * @param Request $request
     * @return array
     */
    public function userDel(Request $request)
    {
        $input = $request->except('_token');
        $user_id = $input['user_id'];
        $res = DB::table('users')->where('user_id', $user_id)->delete();
        if ($res) {
            $data = [
                'status' => 1,
                'msg' => '删除成功'
            ];
            return $data;
        } else {
            $data = [
                'status' => 0,
                'msg' => '删除失败'
            ];
            return $data;
        }
    }

    /**
     * 会员等级列表的显示
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function levelList(Request $request)
    {
        $count = DB::table('user_level')->count();
        $pageObj = new Page($count,$request,"admin/User/levelList");//分页类实例化
        $levelList = DB::table('user_level')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        return view('Admin.User.levelList', compact('levelList', 'pageObj'));
    }

    /**
     * 新增会员等级页面的显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function level()
    {
        return view('Admin.User.level');
    }

    /**
     * 处理新增或编辑会员等级的操作
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function levelHandle(Request $request)
    {
        //新增会员等级的操作
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            $rules = [
                'level_name' => 'required',
            ];
            $message = [
                'level_name.required' => '等级名称不能为空',
            ];
            $v = Validator::make($input, $rules, $message);
            if ($v->passes()) {
                $data = [
                    'level_name' => $input['level_name'],
                    'amount' => $input['amount'],
                    'discount' => $input['discount'],
                    'describe' => $input['describe'],
                ];
                $res = DB::table('user_level')->insert($data);
                if ($res) {
                    return redirect('admin/User/levelList');
                } else {
                    $errors[] = '未知错误，请重试';
                    return back()->with('errors', $errors);
                }
            } else {
                $errors[] = '请检查带有*的信息是否已填写';
                return back()->with('errors', $errors);
            }
        }
        //编辑会员等级的操作
        if ($request->isMethod('put')) {
            $input = $request->except('_token', '_method');
            $level_id = $input['level_id'];
            $data = [
                'level_name' => $input['level_name'],
                'amount' => $input['amount'],
                'discount' => $input['discount'],
                'describe' => $input['describe'],
            ];
            $res = DB::table('user_level')->where('level_id', $level_id)->update($data);
            if ($res) {
                return redirect('admin/User/levelList');
            } else {
                return back();
            }
        }
    }

    /**
     * 删除会员等级信息的操作
     *
     * @param Request $request
     * @return array
     */
    public function levelDel(Request $request)
    {
        $input = $request->except('_token');
        $level_id = $input['level_id'];
        $res = DB::table('user_level')->where('level_id', $level_id)->delete();
        if ($res) {
            $data = [
                'status' => 1,
                'msg' => '删除成功'
            ];
            return $data;
        } else {
            $data = [
                'status' => 0,
                'msg' => '删除失败'
            ];
            return $data;
        }
    }

    /**
     * 编辑会员等级信息页面的显示
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function levelEdit($id)
    {
        $data = DB::table('user_level')->where('level_id', $id)->first();
        return view('Admin.User.levelEdit', compact('data'));
    }

    /**
     * 筛选会员信息的操作
     *
     * @param Request $request
     * @return array
     */
    public function search(Request $request)
    {
        //根据手机号码筛选
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            $mobile = $input['mobile'];
            $res = DB::table('users')->where('mobile', $mobile)->get();
            $str = '';
            if ($res) {
                $level = DB::table('user_level')->get();
                $str .= "<tr role=\"row\" align=\"center\">";
                foreach ($res as $d) {
                    $d->reg_time = date('Y-m-d H:i:s', $d->reg_time);
                    $urlEdit = "userEdit/$d->user_id";
                    $urlDel = "http://www.larashop.com/admin/User/userDel";
                    $str .= "<td><label><input type=\"checkbox\" name=\"user_id\" value=\"{$d->user_id}\">{$d->user_id}</label></td>";
                    $str .= "<td>{$d->nickname}</td>";
                    foreach ($level as $l) {
                        if ($l->level_id == $d->level) {
                            $d->level = $l->level_name;
                            $str .= "<td>{$d->level}</td>";
                        }
                    }
                    $str .= "<td>{$d->total_amount}</td>";
                    $str .= "<td>{$d->email}</td>";
                    $str .= "<td>{$d->mobile}</td>";
                    $str .= "<td>{$d->user_money}</td>";
                    $str .= "<td>{$d->pay_points}</td>";
                    $str .= "<td>{$d->reg_time}</td>";
                    $str .= "<td><a class=\"btn btn-primary\" href=$urlEdit ><i class=\"fa fa-pencil\"></i></a><a class=\"btn btn-danger\" href=\"javascript:void(0)\" data-url=$urlDel data-id=\"{$d->user_id}\" onclick=\"delfun(this)\"><i class=\"fa fa-trash-o\"></i></a></td>";
                }
                $str .= "</tr>";
                $data = [
                    'status' => 1,
                    'msg' => $str
                ];
                return $data;
            } else {
                $data = [
                    'status' => 0,
                    'msg' => '该用户不存在'
                ];
                return $data;
            }
        }
        //根据用户邮箱筛选
        if ($request->isMethod('put')) {
            $input = $request->except('_token');
            $email = $input['email'];
            $res = DB::table('users')->where('email', $email)->get();
            $str = '';
            if ($res) {
                $level = DB::table('user_level')->get();
                $str .= "<tr role=\"row\" align=\"center\">";
                foreach ($res as $d) {
                    $d->reg_time = date('Y-m-d H:i:s', $d->reg_time);
                    $urlEdit = "userEdit/$d->user_id";
                    $urlDel = "http://www.larashop.com/admin/User/userDel";
                    $str .= "<td><label><input type=\"checkbox\" name=\"user_id\" value=\"{$d->user_id}\">{$d->user_id}</label></td>";
                    $str .= "<td>{$d->nickname}</td>";
                    foreach ($level as $l) {
                        if ($l->level_id == $d->level) {
                            $d->level = $l->level_name;
                            $str .= "<td>{$d->level}</td>";
                        }
                    }
                    $str .= "<td>{$d->total_amount}</td>";
                    $str .= "<td>{$d->email}</td>";
                    $str .= "<td>{$d->mobile}</td>";
                    $str .= "<td>{$d->user_money}</td>";
                    $str .= "<td>{$d->pay_points}</td>";
                    $str .= "<td>{$d->reg_time}</td>";
                    $str .= "<td><a class=\"btn btn-primary\" href=$urlEdit ><i class=\"fa fa-pencil\"></i></a><a class=\"btn btn-danger\" href=\"javascript:void(0)\" data-url=$urlDel data-id=\"{$d->user_id}\" onclick=\"delfun(this)\"><i class=\"fa fa-trash-o\"></i></a></td>";
                }
                $str .= "</tr>";
                $data = [
                    'status' => 1,
                    'msg' => $str
                ];
                return $data;
            } else {
                $data = [
                    'status' => 0,
                    'msg' => '该用户不存在'
                ];
                return $data;
            }
        }
    }

    /**
     * 给会员发送邮箱信息页面的显示
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sendMail(Request $request)
    {
        $input = $request->all();
        $user_id = $input['user_id_array'];
        $ids = explode(',', $user_id);
        $arr = [];
        foreach ($ids as $id) {
            $res = DB::table('users')->where('user_id', $id)->select('user_id', 'nickname', 'email')->get();
            $arr[] = $res->toArray();
        }
        return view('Admin.User.sendMail', compact('arr'));
    }

    /**
     * 处理给会员发送邮箱信息的操作
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doSendMail (Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->only('email', 'title', 'text');
            $toEmails = $input['email'];
            $title = $input['title'];
            $text = $input['text'];
            foreach ($toEmails as $toEmail) {
                sleep(1);
                if (!empty($toEmail)) {
                    $data = ['email' => $toEmail, 'title' => $title, 'text' => $text];
                    Mail::send('Admin.User.doSendEmail', $data, function ($message) use ($data) {
                        $message->to($data['email'])->subject($data['title']);
                    });
                } else {
                    continue;
                }
                ob_flush();
            }
            return back();
        }
    }
}
