<?php

namespace Modules\RetailObjects\Http\Controllers;

use App\Actions\CommonControllerAction;
use App\Helpers\CacheKeysHelper;
use App\Helpers\FileDimensionHelper;
use App\Helpers\LanguageHelper;
use App\Helpers\MainHelper;
use App\Models\CategoryPage\CategoryPage;
use App\Models\CategoryPage\CategoryPageTranslation;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\RetailObjects\Models\RetailObject;
use Modules\Team\Http\Requests\TeamStoreRequest;
use Modules\Team\Http\Requests\TeamUpdateRequest;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamTranslation;

class FrontRetailObjectsController extends Controller
{
}
