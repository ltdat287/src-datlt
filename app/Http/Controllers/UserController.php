<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Requests\UserSearchFormRequest;
use App\Http\Requests\UserEditFormRequest;
use App\Http\Requests\UserAddFormRequest;
use Input;
use App\Helpers\MemberHelper;
use Session;
use Redirect;
use Auth;
use Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response [view Top page with data of user]
     */
    public function index()
    {
        // List user employ with non disable DESC </dg>;
        $users = User::getUsers();

        // Redirect to user detail if has employ role.
        if (MemberHelper::getCurrentUserRole() == 'employee')
        {
            $users->where('id', '=', Auth::user()->id);
        }

        // Only listing employ of current user own
        if (MemberHelper::getCurrentUserRole() == 'boss')
        {
            $users->where('boss_id', '=', Auth::user()->id);
        }

        //max record display on page
        $users = $users->paginate(VP_LIMIT_PAGINATE);

        // Set data for view
        $data = array(
            'users'    => $users,
            );

        return view('members.top', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response [view page Add with data of role, boss, user]
     */
    public function create()
    {
        // create array roles to display
        $roles = array(
            'admin'    => ADMIN,
            'boss'     => BOSS,
            'employee' => EMPLOYEE
            );

        // Get bosses
        $bosses = User::getBosses()->get();

        // Get user session
        $user = Session::get('user');

        // Create session for add page
        Session::put('page', 'page_input');

        // Build data for views
        $data = array(
            'roles'  => $roles,
            'bosses' => $bosses,
            'user'   => $user
            );

        return view('members.add', $data);
    }

    /**
     * [POST] Show the member add confirm view.
     *
     * @param UserAddFormRequest $request [check request before return next $request]
     * @return [Responce] [view page add_conf with data of input]
     */
    public function add_conf(UserAddFormRequest $request)
    {
        // Array rules validate for request to add member
        $rules = array(
            'name'               => 'required|min:1|max:16',
            'kana'               => 'required|min:1|max:16',
            'email'              => 'required|max:255|unique:users|vp_email',
            'email_confirmation' => 'required|same:email',
            'telephone_no'       => 'required|vp_telephone|min:10|max:13',
            'birthday'           => 'required|date_format:' . VP_TIME_FORMAT . '|vp_date|min:10|max:10',
            'note'               => 'required|min:1|max:300',
            'password'           => 'required|between:8,32',
            'use_role'           => 'required',
            'boss_id'            => 'boss_with_employee:use_role',
            );

        // Create form input add for member is boss
        if (MemberHelper::getCurrentUserRole() == 'boss') {
            unset($rules['use_role']);
            unset($rules['boss_id']);
        }

        // Check exists of input login post form
        foreach ($rules as $key => $value)
        {
            if (!$request->exists($key)) {
                $errors[] = sprintf(trans('validation.attribute_exists'), trans('validation.attributes.' . $key));

                return view('errors.system_error')->with('errors', $errors);
            }
        }

        // Check boss_id of boss not disabled
        if ($request->has('boss_id')) {
            $boss_id = $request->get('boss_id');
            $user = User::find($boss_id);
            $check_role = $user->role;
            if ($check_role != 'boss') {
                $errors[] = sprintf(trans('validation.input_not_found'), trans('validation.attributes.boss_id'));

                return view('errors.system_error')->with('errors', $errors);
            } else {
                $check_disabled = $user->disabled;
                if ($check_disabled != 0) {
                    $errors[] = sprintf(trans('validation.input_not_found'), trans('validation.attributes.boss_id'));

                    return view('errors.system_error')->with('errors', $errors);
                }
            }
        }

        // Action validate all input with rule for add member
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return back()
            ->withErrors($validator)
            ->withInput();
        }

        // Change session for page to page_confirm
        if (Session::has('page') && Session::get('page') == 'page_input') {
            Session::forget('page');
            Session::put('page', 'page_confirm');
        }

        // Get user data
        $data = self::_confirmUser($request);

        return view('members.add_conf', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request [Check request before return next $request]
     * @return Response [view page add member_comp with message]
     */
    public function store(Request $request)
    {
        // Check session of page_confirm
        if (!Session::has('page') || Session::get('page') != 'page_confirm')
        {
            $errors[] = sprintf(trans('validation.direct_access_page_confirm'));

            return view('errors.system_error')->with('errors', $errors);
        }

        // Clear session of page_confirm
        Session::forget('page');

        // Get user from session
        $user = Session::get('user');

        if (isset(Input::all()['back']) || empty($user)) {
            return Redirect::route('add');
        } else {
            $record = new User();
            $record = self::_saveUser($record, $user);

            // Clear session
            Session::forget('user');

            $message = self::_linkToDetail($record->id) . trans('として追加しました。');
            $data = array(
                'label'   => trans('追加（完了）'),
                'message' => $message
                );

            return view('members.common.member_comp', $data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id [id of member need show]
     * @return Response [view page detail of member]
     */
    public function show($id)
    {
        // Clear user session.
        Session::forget('user');

        $user = User::find($id);

        // Get role.
        $role = $user->role;

        // Get boss.
        $boss = User::find($user->boss_id);

        // Prepare data for view.
        $data = array (
            'user' => $user,
            'role' => $role,
            'boss' => $boss,
            'id'   => $id
            );

        return view('members.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id [id of member need edit]
     * @return Response [view page edit member with data of member]
     */
    public function edit($id)
    {
        // Get roles
        $roles = array(
            'admin'    => ADMIN,
            'boss'     => BOSS,
            'employee' => EMPLOYEE,
            );

        // Get bosses
        $bosses = User::getBosses()->get();

        // Get user from db
        $user = User::find($id);

        // Get user session
        if (Session::has('user') && isset(Session::get('user')->id))
        {
            $user = Session::get('user');
        } else {
            Session::put('user', $user);
        }

         // Create session for add page
        Session::put('page', 'page_input');

        // Prepare data for view.
        $data = array(
            'id'     => $id,
            'roles'  => $roles,
            'bosses' => $bosses,
            'user'   => $user
            );

        return view('members.edit', $data);
    }

    /**
     * [POST] Process validation form edit member submit
     *
     * @param integer $id [id of member need edit]
     * @param UserEditFormRequest $request [check request of edit member before return next $request]
     * @return Responese [view page edit_confirm of member with id of member]
     */
    public function edit_conf($id, UserEditFormRequest $request)
    {
        // Array rules validate for input of UserEditFormRequest
        $rules = array(
            'name'               => 'required|min:1|max:16',
            'kana'               => 'required|min:1|max:16',
            'email'              => 'required|vp_email|max:255|unique:users,email,' . $id,
            'email_confirmation' => 'required|same:email',
            'telephone_no'       => 'required|vp_telephone|min:10|max:13',
            'birthday'           => 'required|date_format:' . VP_TIME_FORMAT . '|vp_date|min:10|max:10',
            'note'               => 'required|min:1|max:300',
            'password'           => 'required|between:8,32',
            'use_role'           => 'required|employee_to_boss:boss_id|boss_to_employee:' . $id,
            'boss_id'            => 'boss_with_employee:use_role',
            );

        // Set form edit for employee member
        if (MemberHelper::getCurrentUserRole() == 'employee') {
            unset($rules['email']);
            unset($rules['email_confirmation']);
            unset($rules['note']);
            unset($rules['use_role']);
            unset($rules['boss_id']);
        }

        // Set form edit for boss member
        if (MemberHelper::getCurrentUserRole() == 'boss') {
            unset($rules['use_role']);
            unset($rules['boss_id']);
        }

        // Check exists of input login post form
        foreach ($rules as $key => $value)
        {
            if (!$request->exists($key)) {
                $errors[] = sprintf(trans('validation.attribute_exists'), trans('validation.attributes.' . $key));

                return view('errors.system_error')->with('errors', $errors);
            }
        }

        // Check value of use_role only in array role
        $roles = array('admin','boss','employee',);
        if ($request->has('use_role')) {
            $role = $request->get('use_role');

            if (!in_array($role, $roles)) {
                $errors[] = sprintf(trans('validation.input_not_found'), trans('validation.attributes.use_role'));

                return view('errors.system_error')->with('errors', $errors);
            }
        }

        // Action validate all input with rule for add member
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return back()
            ->withErrors($validator)
            ->withInput();
        }

        // Get user data
        $data = self::_confirmUser($request);

        // Add ID of user to session of user_edit

        if (Session::has('user'))
        {
            $user = Session::get('user');
            $user->id = $id;
            Session::put('user', $user);
        }

        // Change session for page to page_confirm
        if (Session::has('page') && Session::get('page') == 'page_input') {
            Session::forget('page');
            Session::put('page', 'page_confirm');
        }

        $data['id'] = $id;

        return view('members.edit_conf', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id [id of member need update]
     * @return Response [view page member_comp with messages]
     */
    public function update($id)
    {
        // Check session of page_confirm
        if (!Session::has('page') || Session::get('page') != 'page_confirm')
        {
            $errors[] = sprintf(trans('validation.direct_access_page_confirm'));

            return view('errors.system_error')->with('errors', $errors);
        }

        // Clear session of page_confirm
        Session::forget('page');

        // Get user from session.
        $user   = Session::get('user');

        // If back button of page edit_confirm redirect to page edit
        if (isset(Input::all()['back']) || empty($user)) {

            return redirect('member/' . $id . '/edit');
        } else {
            $record = User::find($id);
            if (! $record) {
                return Redirect::route('not_found');
            }
            $record = self::_updateUser($record, $user);

            // Clear session
            Session::forget('user');

            $message = self::_linkToDetail($record->id) . trans('として追加しました。');
            $data = array(
                'label' => trans('追加（完了）'),
                'message' => $message
                );

            return view('members.common.member_comp', $data);
        }
    }

    /**
     * [POST] Delete user.
     *
     * @param integer $id [id f member need delete]
     * @return Responese [view page delete_conf with data of uesr, role, boss and message errors]
     */
    public function delete_conf($id)
    {
        // Clear user session.
        Session::forget('user');

        // Get user.
        $user = User::find($id);

        // Get role.
        $role = $user->role;

        // Get boss.
        $boss = User::find($user->boss_id);

        // Check current role is BOSS and not same boss_id of user delete.
        $errors = array();
        if (MemberHelper::getCurrentUserRole() == 'boss' && $user->boss_id != Auth::user()->id)
        {
            $errors[] = trans('validation.user_not_delete_boss');
        }

        // Check role of member if BOSS and not has member.
        if ($role == 'boss') {
            $member = User::where('boss_id', $id)->get();
            if (isset($member)) {
                $errors[] = trans('validation.user_not_me_own');
            }
        }

        // Prepare data for view.
        $data = array(
            'id'     => $id,
            'user'   => $user,
            'role'   => $role,
            'boss'   => $boss,
            'errors' => $errors
            );

        return view('members.delete_conf', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $record = User::find($id);

        // Destroy
        $record->disabled = true;
        $record->save();

        $data = array(
            'label' => trans('削除（完了）'),
            'message' => trans('削除しました。')
            );

        return view('members.common.member_comp', $data);
    }

    /**
     * Show the page search user
     * @param  UserSearchFormRequest $request [check validate of input form search before return next $request ]
     * @return [view ('members.search')] [view page search member with resulft of search]
     */
    public function search(UserSearchFormRequest $request)
    {
        $users = null;
        $arr_cons = $arr_vals = null;
        $arr_define = array('name', 'email', 'kana', 'telephone_no');

        // Check roles checked
        $user_ids = array();
        $arr_checked = ['admin', 'boss', 'employee'];
        foreach ($arr_checked as $checked)
        {
            if (Input::has($checked))
            {
                if (Input::get($checked) == 1)
                {
                    $users = User::getUsers()->where('role', '=', $checked)->get();
                    foreach ($users as $user)
                    {
                        if (! in_array($user->id, $user_ids))
                        {
                            $user_ids[] = $user->id;
                        }
                    }
                }
            }
        }

        /*  Set condition for search name, emai, kana, telephone_no */
        foreach ($arr_define as $define)
        {
            if (Input::has($define))
            {
                $arr_cons[] = $define . ' = ?';
                $arr_vals[] = Input::get($define);
            }
        }

        /* Set condition for search birthday */
        if (Input::has('start_date'))
        {
            $arr_cons[] = 'birthday > ?';
            $arr_vals[] = Input::get('start_date');
        }
        if (Input::has('end_date'))
        {
            $arr_cons[] = 'birthday < ?';
            $arr_vals[] = Input::get('end_date');
        }

        // Check conditions exists.
        if ($arr_cons)
        {
            $cons = implode(' AND ', $arr_cons);
        }

        // Get users with user ids.
        $users = User::getUsers();

        // Only listing employ of current user own
        if (MemberHelper::getCurrentUserRole() == 'boss')
        {
            $users->where('boss_id', '=', Auth::user()->id);
        }

        if (count($user_ids))
        {
            $users = $users->whereIn('id', $user_ids);
        }

        // Check exists search conditions.
        if (count($arr_cons))
        {
            $users = $users->whereRaw($cons, $arr_vals);
        }

        // Paginate users.
        if (count($users))
        {
            $users = $users->paginate(VP_LIMIT_PAGINATE)->setPath('search');
        }

        // Get role.
        $roles = array(
            'admin'    => ADMIN,
            'boss'     => BOSS,
            'employee' => EMPLOYEE
            );

        // Build data for view.
        $data = array(
            'users' => $users,
            'roles' => $roles
            );

        return view('members.search', $data);
    }

    /**
     * prepare user data for confirm view.
     *
     * @param object $request [request of user input]
     * @return array [array data of user, role, boss]
     */
    private static function _confirmUser($request)
    {
        // Prepare cache data.
        $user = new \stdClass;
        $user->email              = $request->get('email');
        $user->email_confirmation = $request->get('email_confirmation');
        $user->name               = $request->get('name');
        $user->kana               = $request->get('kana');
        $user->password           = $request->get('password');
        $user->telephone_no       = $request->get('telephone_no');
        $user->birthday           = Carbon::createFromFormat('Y-m-d', $request->get('birthday'));
        $user->note               = ($request->get('note')) ? $request->get('note') : '';
        $user->role               = $request->get('use_role');

        if (MemberHelper::getCurrentUserRole() == 'boss')
        {
            $user->boss_id = MemberHelper::checkLogin()->id;
        }
        else
        {
            $user->boss_id = $request->get('boss_id');
        }

        // Get role.
        $role = $user->role;

        // Get boss.
        $boss = User::find($user->boss_id);

        // Set user from session.
        Session::put('user', $user);

        // Prepare data for view.
        $data = array (
            'user' => $user,
            'role' => $role,
            'boss' => $boss
            );

        return $data;
    }

    /**
     * Get link to member detail page.
     *
     * @param string $userId [id of member]
     * @return string [string html to display on page]
     */
    private static function _linkToDetail($userId = '')
    {
        return html_entity_decode(trans('ID') . ': <a href="' . url('/member/' . $userId . '/detail') . '">' . $userId . '</a>');
    }

    /**
     * Common function for save user.
     *
     * @param object $record [record used to save database]
     * @param object $user [data of member need save]
     * @return object [record to save database]
     */
    private static function _saveUser(&$record, $user)
    {
        if (! $user) {
            return null;
        }

        // Build data.
        $record->email        = $user->email;
        $record->name         = $user->name;
        $record->kana         = $user->kana;
        $record->password     = bcrypt($user->password);
        $record->telephone_no = $user->telephone_no;
        $record->birthday     = $user->birthday;
        $record->note         = ($user->note) ? $user->note : '';
        if (MemberHelper::getCurrentUserRole() == 'boss')
        {
            $user->role  = 'employee';
            $record->boss_id = MemberHelper::checkLogin()->id;
        }
        else
        {
            if ($user->role == 'employee') {
                $record->boss_id = (int) $user->boss_id;
            } else {
                $record->boss_id = 0;
            }
        }

        // Add role for user.
        if (MemberHelper::getCurrentUserRole() != 'employee')
        {
            $record->role = $user->role;
        }

        $record->save();

        return $record;
    }

    /**
     * Common function for update user.
     *
     * @param object $record [record used to save database]
     * @param object $user [data of member need save]
     * @return object [record to save database]
     */
    private static function _updateUser(&$record, $user)
    {
        if (! $user) {
            return null;
        }

        // Build data.
        $record->name         = $user->name;
        $record->kana         = $user->kana;
        $record->password     = bcrypt($user->password);
        $record->telephone_no = $user->telephone_no;
        $record->birthday     = $user->birthday;

        if (MemberHelper::getCurrentUserRole() != 'employee')
        {
            $record->email        = $user->email;
            $record->note         = ($user->note) ? ($user->note) : '';
            if (MemberHelper::getCurrentUserRole() == 'boss')
            {
                $user->role  = 'employee';
                $record->boss_id = MemberHelper::checkLogin()->id;
            }
            else
            {
                if ($user->role == 'employee') {
                    $record->boss_id = (int) $user->boss_id;
                } else {
                    $record->boss_id = 0;
                }
            }

            // Add role for user.
            $record->role = $user->role;
        }

        $record->save();

        return $record;
    }
}
