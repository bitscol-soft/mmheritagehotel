<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Group;
use App\Traits\FileSaver;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\CheckPermission;

class GroupController extends Controller
{
    use CheckPermission, FileSaver;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->hasAccess("groups.view");  // check permission

        $groups = Group::latest()->paginate(30);
        return view('global.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->hasAccess("groups.create");  // check permission
        return view('global.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $group = new Group();
        $group->validate($request);

        try {
            $slug = Str::slug($request->name, '_');
            $logo = $request->file('logo');

            if (isset($logo)) {
                $currentDate = Carbon::now()->toDateString();
                $logoName   = $slug . '_' . $currentDate . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
                if (!file_exists('uploads/group')) {
                    mkdir('uploads/group', 0777, true);
                }
                $logo->move('uploads/group', $logoName);
                $group->store($request, $logoName);
            } else {
                $logoName = 'default.png';
                $group->store($request, $logoName);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
        return redirect()->back()->with('message', 'Group Added Successful');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->hasAccess("groups.edit");  // check permission

        $group = Group::findOrFail($id);
        return view('global.groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $group = Group::find($id);

        $group->validateUpdate($request, $id);

        $groupLogo = $group->where('id', $id)->select('logo')->firstOrFail();

        try {
            $slug = Str::slug($request->name, '_');
            $logo = $request->file('logo');
            if (isset($logo)) {
                $currentDate = Carbon::now()->toDateString();
                $logoName   = $slug . '_' . $currentDate . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
                if (!file_exists('uploads/group')) {
                    mkdir('uploads/group', 0777, true);
                }
                if (file_exists('uploads/group/' . $groupLogo->logo)) {
                    @unlink('uploads/group/' . $groupLogo->logo);
                }

                $logo->move('uploads/group', $logoName);
                $group->storeUpdate($request, $logoName, $id);
            } else {
                $logoName = $groupLogo->logo;
                $group->storeUpdate($request, $logoName, $id);
            }

            $this->upload_file($request->fav_icon, $group, 'fav_icon', 'uploads/group');

            $this->upload_file($request->login_background_image, $group, 'login_background_image', 'uploads/group');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('group.index')->with('message', 'Group Update Successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->hasAccess("groups.delete");  // check permission
        $group = Group::findOrFail($id);
        if ($group->id == 1) {
            return redirect()->back()->with('error', 'You can not delete this Group.');

        }
        try {
            $group->delete();
            if (file_exists('uploads/group/' . $group->logo)) {
                @unlink('uploads/group/' . $group->logo);
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('error', 'You can not delete this Group Because this Group is Use in another table.');
            } else {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        return redirect()->back()->with('message', 'Group Delete Successful');
    }

    public function printGroups()
    {
        $groups = Group::with('user')->get();
        return view('print_layouts.groups', compact('groups'));
    }
}
