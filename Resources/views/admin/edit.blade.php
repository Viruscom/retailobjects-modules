@extends('layouts.admin.app')
@section('styles')
    <link href="{{ asset('admin/assets/css/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('admin/assets/css/multi-select.css') }}" rel="stylesheet"/>
    <link href="{{ asset('admin/plugins/foundation-datepicker/datepicker.css') }}" rel="stylesheet"/>
@endsection

@section('scripts')
    <script src="{{ asset('admin/assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('admin/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/plugins/foundation-datepicker/datepicker.js') }}"></script>
    <script>
        $(".select2").select2({language: "bg"});

        var focusedEditor;
        CKEDITOR.timestamp = new Date();
        CKEDITOR.on('instanceReady', function (evt) {
            var editor = evt.editor;
            editor.on('focus', function (e) {
                focusedEditor = e.editor.name;
            });
        });
        var nowTemp  = new Date();
        var now      = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
        var checkin  = $('#dpd1').fdatepicker({
            onRender: function (date) {
                return '';
            },
            format: 'yyyy-mm-dd'
        }).on('changeDate', function (ev) {
            if (ev.date.valueOf() > checkout.date.valueOf()) {
                var newDate = new Date(ev.date)
                newDate.setDate(newDate.getDate() + 1);
                checkout.update(newDate);
            }
            checkin.hide();
            $('#dpd2')[0].focus();
        }).data('datepicker');
        var checkout = $('#dpd2').fdatepicker({
            onRender: function (date) {
                return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
            },
            format: 'yyyy-mm-dd'
        }).on('changeDate', function (ev) {
            checkout.hide();
        }).data('datepicker');
        $('#oneDayEventPicker').fdatepicker({
            onRender: function (date) {
                return '';
            },
            format: 'yyyy-mm-dd'
        });
    </script>
@endsection

@section('content')
    @include('admin.pages.breadcrumbs')
    @include('admin.notify')
    <form class="my-form" action="{{ route('admin.pages.update', ['id' => $contentPage->id]) }}" method="POST" data-form-type="update" enctype="multipart/form-data" autocomplete="off">
        <div class="col-xs-12 p-0">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="position" value="{{(old('position')) ? old('position') : $contentPage->position}}">
            <div class="navigation-id-old hidden">{{old('category_page_id')}}</div>
            <div class="navigation-id-current hidden">{{$contentPage->category_page_id}}</div>
            <div class="one-day-event-old hidden">{{ old('one_day_event') }}</div>

            <div class="bg-grey top-search-bar">
                <div class="action-mass-buttons pull-right">
                    <button type="submit" name="submit" value="submit" class="btn btn-lg save-btn margin-bottom-10"><i class="fas fa-save"></i></button>
                    <a href="{{ url()->previous() }}" role="button" class="btn btn-lg back-btn margin-bottom-10"><i class="fa fa-reply"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label page-label col-md-3"><span class="text-purple">* </span>Категория Страница:</label>
                <div class="col-md-4">
                    <select class="form-control select2 inner-page-contents-select navigation_type_select" name="category_page_id">
                        @foreach($pageCategories as $pageCategory)
                            <option value="{{ $pageCategory->id }}" {{ $pageCategory->id === $contentPage->categoryPage->id ? 'selected': '' }} visualization_type="{{ $pageCategory->visualization_type_id }}">{{ $pageCategory->title}}</option>
                        @endforeach
                    </select>
                    <span class="hidden old-category-page-id">{{ old('category_page_id') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning"><strong>Внимание! </strong>Промяната на загланието (името) или на активността (видимостта) на страницата ще се отрази в sitemap-a на сайта и може да доведе до промени в индексирането на Вашия сайт от търсачките.</div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <ul class="nav nav-tabs nav-tabs-first">
                    @foreach($languages as $language)
                        <li @if($language->code === config('default.app.language.code')) class="active" @endif><a data-toggle="tab" href="#{{$language->code}}">{{$language->code}} <span class="err-span-{{$language->code}} hidden text-purple"><i class="fas fa-exclamation"></i></span></a></li>
                    @endforeach
                </ul>
                <div class="tab-content m-b-0">
                    @foreach($languages as $language)
                        <div id="{{$language->code}}" class="tab-pane fade in @if($language->code === config('default.app.language.code')) active @endif">
                            @include('admin.partials.on_edit.form_fields.input_text', ['model'=> $contentPage, 'fieldName' => 'title_' . $language->code, 'label' => trans('admin.title'), 'required' => true])
                            @include('admin.partials.on_edit.form_fields.textarea', ['model'=> $contentPage, 'fieldName' => 'announce_' . $language->code, 'rows' => 9, 'label' => trans('admin.description'), 'required' => false])
                            @include('admin.partials.on_edit.form_fields.textarea', ['model'=> $contentPage, 'fieldName' => 'description_' . $language->code, 'rows' => 9, 'label' => trans('admin.description'), 'required' => false])
                            @include('admin.partials.on_edit.show_in_language_visibility_checkbox', ['model'=> $contentPage, 'fieldName' => 'visible_' . $language->code])

                            <div class="additional-textareas-wrapper">
                                <hr>
                                <h3>{{ __('admin.common.additional_texts') }}</h3>
                                <div class="panel-group" id="accordion-{{$language->id}}">
                                    @for($i=1; $i<7; $i++)
                                        @include('admin.partials.on_edit.additional_title_and_text', ['model' => $contentPage, 'language' => $language, 'i' => $i])
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <ul class="nav nav-tabs-second">
                    @foreach($languages as $language)
                        <li @if($language->code === config('default.app.language.code')) class="active" @endif><a langcode="{{$language->code}}">{{$language->code}}</a></li>
                    @endforeach
                </ul>
                <div class="form form-horizontal">
                    <div class="form-body">
                        <div class="form-group insertFileContainer">
                            <label class="control-label col-md-3"><i class="fas fa-file"></i> Вмъкване на файл в едитора:</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="file_title" class="form-control input-sm file-title" placeholder="Заглавие на файл">
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px">
                                    <div class="col-md-4">
                                        <select class="form-control file-select select2" name="file" id="fileName">
                                            <option disabled="" selected="" value=""> {{ __('admin.files.choose_file') }}</option>
                                            @foreach($files as $file)
                                                <option>{{$file->filename}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px">
                                    <div class="col-md-4">
                                        <button id="fileInsert" data-editor="" folder-path="{{ $filesPathUrl }}" name="file_insert" class="btn btn-sm grey margin-bottom-10"><i class="fa fa-upload"></i> вмъкни файл</button>
                                        <p class="help-block">1. Изберете заглавие на файла</p>
                                        <p class="help-block">2. Изберете файл от падащото меню</p>
                                        <p class="help-block">3. Кликнете в едитора, където искате да се покаже файла.</p>
                                        <p class="help-block">4. Натиснете бутонът "Вмъкни файл".</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (array_key_exists('Catalogs', $activeModules) && isset($mainCatalogs))
                            <hr>
                            <div class="form-group catalogInsert">
                                <label class="control-label col-md-3"><i class="fas fa-book-open"></i> Вмъкване на каталог в едитора:</label>
                                <div class="col-md-9">
                                    <div class="row" style="margin-top: 10px">
                                        <div class="col-md-4">
                                            <select class="form-control file-select select2" name="file" id="catalogName">
                                                <option disabled="" selected="" value=""> избери каталог</option>
                                                @foreach($mainCatalogs as $mainCatalog)
                                                    @php
                                                        $catalogTranslation = $mainCatalog->translations()->where('language_id', 1)->first();
                                                    @endphp
                                                    <option value="{{ $mainCatalog->id }}">{{$catalogTranslation->short_description}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px">
                                        <div class="col-md-4">
                                            <button id="catalogInsertBtn" data-editor="" folder-path="{{ $filesPathUrl }}" name="file_insert" class="btn btn-sm grey margin-bottom-10"><i class="fa fa-upload"></i> вмъкни каталог</button>
                                            <p class="help-block">1. Изберете каталог от падащото меню</p>
                                            <p class="help-block">2. Кликнете в едитора, където искате да се покаже файла.</p>
                                            <p class="help-block">3. Натиснете бутонът "Вмъкни каталог".</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <hr>
                        <div class="form-group @if($errors->has('price')) has-error @endif">
                            <label class="control-label col-md-3">Цена (0.00):</label>
                            <div class="col-md-3">
                                <input class="form-control" type="number" step="0.01" name="price" value="{{ old('price') ? old('price') : $contentPage->price }}">
                                @if($errors->has('price'))
                                    <span class="help-block">{{ trans($errors->first('price')) }}</span>
                                @endif
                                <div class="col-md-12 m-t-10 p-l-0">
                                    <div class="pretty p-default p-square">
                                        <input type="checkbox" name="from_price" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="Активирай/Деактивирай от цена" data-trigger="hover" {{ ($contentPage->from_price) ? 'checked' : '' }} />
                                        <div class="state p-primary">
                                            <label>Активирай "От цена"</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group @if($errors->has('one_day_event_date')) has-error @endif one-day-event hidden">
                            <label class="control-label col-md-3">Едондневно събитие:</label>
                            <div class="col-md-3">
                                <div class="input-group m-b-10">
                                    <div class="input-group-addon">Дата</div>
                                    <input type="text" class="form-control" value="{{ $contentPage->one_day_event_date }}" name="one_day_event_date" id="oneDayEventPicker">
                                </div>
                                @if($errors->has('one_day_event_date'))
                                    <span class="help-block">{{ trans($errors->first('one_day_event_date')) }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if($errors->has('date_from_to')) has-error @endif period-event">
                            <label class="control-label col-md-3">За период (от-до):</label>
                            <div class="col-md-3">
                                <div class="input-group m-b-10">
                                    <div class="input-group-addon">От дата</div>
                                    <input type="text" class="form-control" value="{{ $contentPage->from_date }}" name="from_date" id="dpd1">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-addon">До дата</div>
                                    <input type="text" class="form-control" value="{{ $contentPage->to_date }}" name="to_date" id="dpd2">
                                </div>
                                @if($errors->has('date_from_to'))
                                    <span class="help-block">{{ trans($errors->first('date_from_to')) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Тип събитие:</label>
                            <div class="col-md-3 m-t-10 p-l-0">
                                <div class="hidden one-day-event-div">{{$contentPage->one_day_event}}</div>
                                <div class="pretty p-default p-square">
                                    <input type="checkbox" name="one_day_event" class="tooltips one-day-event-checkbox" data-toggle="tooltip" data-placement="right" data-original-title="Активирай/Деактивирай еднодневно събитие" data-trigger="hover" {{ old('one_day_event') ? old('one_day_event') : ($contentPage->one_day_event ? 'checked' : '') }} />
                                    <div class="state p-primary">
                                        <label>Еднодневно събитие</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @include('admin.partials.on_edit.form_fields.upload_file', ['model' => $contentPage, 'deleteRoute' => route('admin.pages.delete-image', ['id'=>$contentPage->id])])
                        <hr>
                        @include('admin.partials.on_edit.active_checkbox', ['model' => $contentPage])
                        <hr>
                        @include('admin.partials.on_edit.position_in_site_button', ['model' => $contentPage, 'models' => $contentPage->categoryPage->pages])

                    </div>
                    @include('admin.partials.on_edit.form_actions_bottom')

                </div>
            </div>
        </div>
    </form>
@endsection
