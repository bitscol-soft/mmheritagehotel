<?php

use Module\Permission\Models\Module;

function slugs()
{
    return auth()->user()->permissions()->pluck('slug')->toArray();
}

function active_modules()
{
    return Module::active()->get();
}


function active_module_ids()
{
    return active_modules()->pluck('id')->toArray();
}


function hasPermissionV2($slug)
{
    if (isSystemAdmin()) {
           return true;
    } else {
          return in_array($slug, slugs());
    }
}


// check permission for multiple slugs
function hasAnyPermissionV2($input_slugs)
{
    if (isSystemAdmin()) {
           return true;
    } else {

        if(count(array_intersect(slugs(), $input_slugs)) !== 0) {
            return true;
        }

        return false;
    }
}


// check permission for single slug
function hasModulePermissionV2($module_name)
{
    return in_array($module_name, active_module_ids());
}

