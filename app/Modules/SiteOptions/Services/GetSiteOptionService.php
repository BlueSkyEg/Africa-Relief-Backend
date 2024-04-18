<?php

namespace App\Modules\SiteOptions\Services;

use App\Modules\SiteOptions\SiteOptionRepository;

class GetSiteOptionService
{
    public function __construct(private readonly SiteOptionRepository $siteOptionRepository)
    {
    }

    public function getSiteOption(string $name)
    {
        return $this->siteOptionRepository->getSiteOption($name);
    }

    public function getQuickbooksOptions()
    {
        return $this->siteOptionRepository->getQuickbooksOptions();
    }
}
