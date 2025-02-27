<?php

namespace App\Modules\SiteOptions\Repositories;

use App\Models\SiteOption;

class SiteOptionRepository
{
    public function getSiteOption(string $name)
    {
        return SiteOption::where('name', $name)->first();
    }

    public function updateSiteOption(string $name, string $value)
    {
        return SiteOption::where('name', $name)->update(['value' => $value]);
    }

    public function getQuickbooksOptions()
    {
        return SiteOption::where('name', 'like', 'quickbooks_%')->get()->groupBy('name');
    }
}
