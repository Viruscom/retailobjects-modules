@extends('layouts.admin.app')
@section('styles')
    <link href="{{ asset('admin/assets/css/select2.min.css.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('admin/assets/css/multi-select.css.css') }}" rel="stylesheet"/>
    <link href="{{ asset('admin/plugins/foundation-datepicker/datepicker.css') }}" rel="stylesheet"/>
@endsection

@section('scripts')
    <script src="{{ asset('admin/assets/js/select2.min.js.js') }}"></script>
    <script src="{{ asset('admin/assets/js/jquery.multi-select.js.js') }}"></script>
    <script src="{{ asset('admin/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/plugins/foundation-datepicker/datepicker.js') }}"></script>
    <script>
        $(".select2").select2({language: "bg"});
        //Check for errors and mark tab
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
        $(document).ready(function () {
            $('.one-day-event-checkbox').trigger('click');
        });
    </script>
@endsection

@section('content')
    @include('admin.pages.breadcrumbs')
    @include('admin.notify')
    <form class="my-form" action="{{ route('admin.pages.store') }}" method="POST" data-form-type="store" enctype="multipart/form-data" autocomplete="off">
        <span class="hidden curr-editor"></span>
        <div class="col-xs-12 p-0">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="position" value="{{old('position')}}">
            <input type="hidden" name="category_type" value="{{old('category_type')}}">

            @include('admin.partials.on_create.form_actions_top')

        </div>
        <div class="col-md-12">
            <div class="form-group  @if($errors->has('category_page_id')) has-error @endif">
                <label class="control-label page-label col-md-3"><span class="text-purple">* </span>Категория Страница:</label>
                <div class="col-md-4">
                    @if(is_null($pageCategories))
                        <div class="alert alert-warning"><strong>{{ __('admin.common.warning') }}</strong>{{ __('admin.pages.first_create_category_page_warning') }}</div>
                    @else
                        <select class="form-control select2 inner-page-contents-select navigation_type_select" name="category_page_id">
                            @forelse($pageCategories as $pageCategory)
                                <option value="{{ $pageCategory->id }}" visualization_type="{{ $pageCategory->visualization_type_id }}">{{ $pageCategory->title}}</option>
                            @empty
                                <option value="">Моля, добавете категория</option>
                            @endforelse
                        </select>
                    <span class="hidden old-category-page-id">{{ old('category_page_id') }}</span>
                        @if($errors->has('category_page_id'))
                            <span class="help-block">{{ trans($errors->first('category_page_id')) }}</span>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <ul class="nav nav-tabs nav-tabs-first">
                    @foreach($languages as $language)
                        <li @if($language->code === config('default.app.language.code')) class="active" @endif>
                            <a data-toggle="tab" href="#{{$language->code}}">
                                {{$language->code}} <span class="err-span-{{$language->code}} hidden text-purple"><i class="fas fa-exclamation"></i></span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content m-b-0">
                    @foreach($languages as $language)
                        <div id="{{$language->code}}" class="tab-pane fade in @if($language->code === config('default.app.language.code')) active @endif">
                            @include('admin.partials.on_create.form_fields.input_text', ['fieldName' => 'title_' . $language->code, 'label' => trans('admin.title'), 'required' => true])
                            @include('admin.partials.on_create.form_fields.textarea', ['fieldName' => 'announce_' . $language->code, 'rows' => 9, 'label' => trans('admin.announce'), 'required' => false])
                            @include('admin.partials.on_create.form_fields.textarea', ['fieldName' => 'description_' . $language->code, 'rows' => 9, 'label' => trans('admin.description'), 'required' => false])
                            @include('admin.partials.on_create.show_in_language_visibility_checkbox', ['fieldName' => 'visible_' . $language->code])

                            <div class="additional-textareas-wrapper">
                                <hr>
                                <h3>{{ __('admin.common.additional_texts') }}</h3>
                                <div class="panel-group" id="accordion-{{$language->id}}">
                                    @for($i=1; $i<7; $i++)
                                        @include('admin.partials.on_create.additional_titles_and_texts', ['language' => $language, 'i' => $i])
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
                        <div class="row">
                            <div class="form-group insertFileContainer col-md-6">
                                <div class="col-md-12">
                                    <h3 class="m-t-40 m-b-20"><i class="fas fa-file fa-2x"></i> {{ __('admin.files.index') }}</h3>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="m-t-10 m-b-10">{{ __('admin.files.import_file_in_editor') }}:</div>
                                            <label for="file_title" class="m-t-10">{{ __('admin.files.title_of_file') }}</label>
                                            <input type="text" name="file_title" id="file_title" class="form-control file-title" placeholder="Заглавие на файл">
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px">
                                        <div class="col-md-12">
                                            <label for="fileName">{{ __('admin.files.file') }}</label><select class="form-control file-select select2" name="file" id="fileName">
                                                <option disabled="" selected="" value=""> {{ __('admin.files.choose_file') }}</option>
                                                @foreach($files as $file)
                                                    <option>{{$file->filename}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px">
                                        <div class="col-md-12">
                                            <button id="fileInsert" data-editor="" folder-path="{{ $filesPathUrl }}" name="file_insert" class="btn btn-sm grey margin-bottom-10"><i class="fa fa-upload"></i> {{ __('admin.files.import_file') }}</button>
                                            <p class="help-block">{{ __('admin.files.help_step_1') }}</p>
                                            <p class="help-block">{{ __('admin.files.help_step_2') }}</p>
                                            <p class="help-block">{{ __('admin.files.help_step_3') }}</p>
                                            <p class="help-block">{{ __('admin.files.help_step_4') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (array_key_exists('Catalogs', $activeModules) && isset($mainCatalogs))
                                <div class="form-group catalogInsert col-md-6">
                                    <div class="col-md-12">
                                        <h3 class="m-t-40 m-b-20"><i class="fas fa-book-open fa-2x"></i> {{ __('admin.catalogs') }}</h3>
                                        <div class="row" style="margin-top: 10px">
                                            <div class="col-md-12">
                                                <div class="m-t-10 m-b-10">Вмъкване на каталог в едитора:</div>
                                                <label for="catalogName">Каталог</label><select class="form-control file-select select2" name="file" id="catalogName">
                                                    <option disabled="" selected="" value=""> избери каталог</option>
                                                    @foreach($mainCatalogs as $mainCatalog)
                                                        <option value="{{ $mainCatalog->id }}">{{$mainCatalog->short_description}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <div class="col-md-12">
                                                <button id="catalogInsertBtn" data-editor="" folder-path="{{ $filesPathUrl }}" name="file_insert" class="btn btn-sm grey margin-bottom-10"><i class="fa fa-upload"></i> вмъкни каталог</button>
                                                <p class="help-block">1. Изберете каталог от падащото меню</p>
                                                <p class="help-block">2. Кликнете в едитора, където искате да се покаже файла.</p>
                                                <p class="help-block">3. Натиснете бутонът "Вмъкни каталог".</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <hr>
                        <div class="form-group @if($errors->has('price')) has-error @endif">
                            <label class="control-label col-md-3">Цена (0.00):</label>
                            <div class="col-md-3">
                                <input class="form-control" type="number" step="0.01" name="price" value="{{ old('price') }}">
                                @if($errors->has('price'))
                                    <span class="help-block">{{ trans($errors->first('price')) }}</span>
                                @endif
                                <div class="col-md-12 m-t-10 p-l-0">
                                    <div class="pretty p-default p-square">
                                        <input type="checkbox" name="from_price" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="Активирай/Деактивирай от цена" data-trigger="hover"/>
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
                                    <input type="text" class="form-control" value="" name="one_day_event_date" id="oneDayEventPicker">
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
                                    <input type="text" class="form-control" value="" name="from_date" id="dpd1">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-addon">До дата</div>
                                    <input type="text" class="form-control" value="" name="to_date" id="dpd2">
                                </div>
                                @if($errors->has('date_from_to'))
                                    <span class="help-block">{{ trans($errors->first('date_from_to')) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Тип събитие:</label>
                            <div class="col-md-3 m-t-10 p-l-0">
                                <div class="pretty p-default p-square">
                                    <input type="checkbox" name="one_day_event" class="tooltips one-day-event-checkbox" data-toggle="tooltip" data-placement="right" data-original-title="Активирай/Деактивирай еднодневно събитие" data-trigger="hover"/>
                                    <div class="state p-primary">
                                        <label>Еднодневно събитие</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <hr>
                            </div>

                            <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-3">{{ __('admin.image') }}:</label>
                                    <div class="col-md-9">
                                        <input type="file" name="image" class="filestyle" data-buttonText="@lang('admin.browse_file')" data-iconName="fas fa-upload" data-buttonName="btn green" data-badge="true">
                                        <p class="help-block file-rule file-rules-0">{!! $fileRulesInfo[1] !!}</p>
                                        <p class="help-block file-rule file-rules-1 hidden">{!! $fileRulesInfo[1] !!}</p>
                                        <p class="help-block file-rule file-rules-2 hidden">{!! $fileRulesInfo[2] !!}</p>
                                        <p class="help-block file-rule file-rules-3 hidden">{!! $fileRulesInfo[3] !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @include('admin.partials.on_create.active_checkbox')
                    </div>
                    @include('admin.partials.on_create.form_actions_bottom')
                </div>
            </div>
        </div>
    </form>
@endsection
