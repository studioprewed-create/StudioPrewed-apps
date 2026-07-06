<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\FrontPage\DashboardTrait;
use App\Http\Controllers\Traits\FrontPage\PortfolioTrait;
use App\Http\Controllers\Traits\FrontPage\PricelistTrait;
use App\Http\Controllers\Traits\FrontPage\CatalogTrait;
use App\Http\Controllers\Traits\FrontPage\SurveyTrait;
use App\Http\Controllers\Traits\FrontPage\AccountTrait;

class FRONTPAGEController extends Controller
{
    use DashboardTrait;
    use PortfolioTrait;
    use PricelistTrait;
    use CatalogTrait;
    use AccountTrait;
    use SurveyTrait;
}