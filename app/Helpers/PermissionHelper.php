<?php

function p_slugs()
{
    return auth()->user()->permissions()->pluck('slug')->toArray();
}


// permission check from view/blade
// check permission for single slug
function hasPermission ($slug, $permission_slugs=null)
{

    // return true;

    if (isSystemAdmin()) {
           return true;
    } else {
        if ($permission_slugs == null) {
            $permission_slugs = permissionSlug();
        }
          return in_array($slug, $permission_slugs);
    }
}


// check permission for multiple slugs
function hasAnyPermission ($input_slugs, $permission_slugs)
{
    // return true;

    if (isSystemAdmin()) {
           return true;
    } else {

        if(count(array_intersect($permission_slugs, $input_slugs)) !== 0) {
            return true;
        }

        return false;
    }
}


// check permission for single slug
function hasModulePermission ($module_name, $activae_modules)
{
    // return true;

    return in_array($module_name, $activae_modules);
}



function hasmerchandisingSetupPermission($slugs)
{
    // return true;
    return hasAnyPermission(['item.units.index', 'seasons.index', 'buyers.index', 'yarns.index',
                            'ggs.index', 'gsms.index', 'fabric.compositions.index', 'fabric.constructions.index',
                            'document.types.index', 'trim.types.index', 'trim.details.index', 'fright.modes.index', 'sample.types.index', 'couriers.index'
                            ], $slugs);
}

function hasInventotyModulePermission($slugs, $active_modules)
{
    // return true;
    return hasModulePermission('Inventory', $active_modules)
            && hasAnyPermission([
                'arp.index', 'dyeing.orders.index', 'subcontract.work.orders.index', 'knit.yarn.orders.index', 'woven.fabric.orders.index',

                'arp.good.receives.index', 'arp.reports.index',
                'yarn.good.receives.index', 'yarn.reports.ledger', 'yarn.reports.stock-in-hand',
                'subcontract.good.receives.index',
                'knit.yarn.good.receives.index', 'knit.yarn.reports.ledger', 'knit.yarn.reports.stock-in-hand',
                'woven.fabric.good.receives.index', 'woven.fabric.good.receives.ledger'
        ], $slugs);
}


function permissionSlug()
{
    return auth()->user()->permissions()->pluck('slug')->toArray();
}
