<?php

namespace App\Modules\SiteOptions\Services;

use App\Modules\SiteOptions\Repositories\SiteOptionRepository;

class SiteOptionService
{
    public function __construct(private readonly SiteOptionRepository $siteOptionRepository)
    {
    }

    public function getSiteOption(string $name)
    {
        return $this->siteOptionRepository->getSiteOption($name);
    }


    public function updateSiteOption(string $name, string $value)
    {
        return $this->siteOptionRepository->updateSiteOption($name, $value);
    }


    public function getQuickbooksOptions()
    {
        return $this->siteOptionRepository->getQuickbooksOptions();
    }
}
