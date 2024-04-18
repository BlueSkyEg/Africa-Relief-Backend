<?php

namespace App\Modules\SiteOptions\Services;

use App\Modules\SiteOptions\SiteOptionRepository;

class UpdateSiteOptionService
{
    public function __construct(private readonly SiteOptionRepository $siteOptionRepository)
    {
    }

    public function updateSiteOption(string $name, string $value)
    {
        return $this->siteOptionRepository->updateSiteOption($name, $value);
    }
}
