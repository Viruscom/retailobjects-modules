<?php

namespace Modules\RetailObjects\Http\Controllers;

use App\Actions\CommonControllerAction;
use App\Helpers\CacheKeysHelper;
use App\Helpers\FileDimensionHelper;
use App\Helpers\LanguageHelper;
use App\Helpers\MainHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\RetailObjects\Http\Requests\RetailObjectStoreRequest;
use Modules\RetailObjects\Http\Requests\RetailObjectsUpdateRequest;
use Modules\RetailObjects\Models\RetailObject;
use Modules\RetailObjects\Models\RetailObjectTranslation;
use Modules\Team\Http\Requests\TeamStoreRequest;
use Modules\Team\Http\Requests\TeamUpdateRequest;

class RetailObjectsController extends Controller
{
    public function index()
    {
        if (is_null(Cache::get(CacheKeysHelper::$RETAIL_OBJECT_ADMIN))) {
            RetailObject::cacheUpdate();
        }

        return view('retailobjects::admin.index', ['retailObjects' => Cache::get(CacheKeysHelper::$RETAIL_OBJECT_ADMIN)]);
    }
    public function create()
    {
        return view('retailobjects::admin.create', [
            'languages'     => LanguageHelper::getActiveLanguages(),
            'fileRulesInfo' => RetailObject::getUserInfoMessage()
        ]);
    }
    public function store(RetailObjectStoreRequest $request, CommonControllerAction $action): RedirectResponse
    {
        if ($request->has('image')) {
            $request->validate(['image' => FileDimensionHelper::getRules('RetailObjects', 1)], FileDimensionHelper::messages('RetailObjects', 1));
        }
        $team = $action->doSimpleCreate(RetailObject::class, $request);
        $action->updateUrlCache($team, RetailObjectTranslation::class);
        $action->storeSeo($request, $team, 'RetailObjects');
        RetailObject::cacheUpdate();

        $team->storeAndAddNew($request);

        return redirect()->route('admin.retail-objects.index')->with('success-message', trans('admin.common.successful_create'));
    }
    public function edit($id)
    {
        $retailObject = RetailObject::whereId($id)->with('translations')->first();
        MainHelper::goBackIfNull($retailObject);

        return view('retailobjects::admin.edit', [
            'retailObject'    => $retailObject,
            'languages'     => LanguageHelper::getActiveLanguages(),
            'fileRulesInfo' => RetailObject::getUserInfoMessage()
        ]);
    }
    public function deleteMultiple(Request $request, CommonControllerAction $action): RedirectResponse
    {
        if (!is_null($request->ids[0])) {
            $action->deleteMultiple($request, RetailObject::class);

            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
        }

        return redirect()->back()->withErrors(['admin.common.no_checked_checkboxes']);
    }
    public function activeMultiple($active, Request $request, CommonControllerAction $action): RedirectResponse
    {
        $action->activeMultiple(RetailObject::class, $request, $active);
        RetailObject::cacheUpdate();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }
    public function update($id, RetailObjectsUpdateRequest $request, CommonControllerAction $action): RedirectResponse
    {
        $retailObject = RetailObject::whereId($id)->with('translations')->first();
        MainHelper::goBackIfNull($retailObject);

        $action->doSimpleUpdate(RetailObject::class, RetailObjectTranslation::class, $retailObject, $request);
        $action->updateUrlCache($retailObject, RetailObjectTranslation::class);
        $action->updateSeo($request, $retailObject, 'RetailObjects');

        if ($request->has('image')) {
            $request->validate(['image' => FileDimensionHelper::getRules('RetailObjects', 1)], FileDimensionHelper::messages('RetailObjects', 1));
            $retailObject->saveFile($request->image);
        }

        RetailObject::cacheUpdate();

        return redirect()->route('admin.retail-objects.index')->with('success-message', 'admin.common.successful_edit');
    }
    public function active($id, $active): RedirectResponse
    {
        $retailObject = RetailObject::find($id);
        MainHelper::goBackIfNull($retailObject);

        $retailObject->update(['active' => $active]);
        RetailObject::cacheUpdate();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }
    public function delete($id, CommonControllerAction $action): RedirectResponse
    {
        $retailObject = RetailObject::where('id', $id)->first();
        MainHelper::goBackIfNull($retailObject);

        $action->deleteFromUrlCache($retailObject);
        $action->delete(RetailObject::class, $retailObject);

        return redirect()->back()->with('success-message', 'admin.common.successful_delete');
    }
    public function positionUp($id, CommonControllerAction $action): RedirectResponse
    {
        $retailObject = RetailObject::whereId($id)->with('translations')->first();
        MainHelper::goBackIfNull($retailObject);

        $action->positionUp(RetailObject::class, $retailObject);
        RetailObject::cacheUpdate();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }
    public function positionDown($id, CommonControllerAction $action): RedirectResponse
    {
        $retailObject = RetailObject::whereId($id)->with('translations')->first();
        MainHelper::goBackIfNull($retailObject);

        $action->positionDown(RetailObject::class, $retailObject);
        RetailObject::cacheUpdate();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }
    public function deleteImage($id, CommonControllerAction $action): RedirectResponse
    {
        $retailObject = RetailObject::find($id);
        MainHelper::goBackIfNull($retailObject);

        if ($action->imageDelete($retailObject, RetailObject::class)) {
            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
        }

        return redirect()->back()->withErrors(['admin.image_not_found']);
    }
}
