<?php

namespace App\Models;

use App\Model;

class Group extends Model
{
    

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function validate($request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'nullable|unique:groups,email|max:100',
            'phone' => 'nullable|unique:groups,phone|max:20',
            'logo'  => 'nullable|max:1024',
        ]);
    }

    public function store($request, $logoName)
    {
        $this->create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'logo'      => $logoName,
        ]);
    }

    public function validateUpdate($request, $id)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'nullable|unique:groups,email,' . $id . '|max:100',
            'phone' => 'nullable|unique:groups,phone,' . $id . '|max:20',
            'logo'  => 'nullable|max:1024',
        ]);
    }

    public function storeUpdate($request, $logoName, $id)
    {
        $this->where('id', $id)->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'logo'      => $logoName,
        ]);
    }
}
