@extends('layouts.admin.app')
@section('scripts')
    <script src="{{ asset('admin/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        var focusedEditor;
        CKEDITOR.timestamp = new Date();
        CKEDITOR.on('instanceReady', function (evt) {
            var editor = evt.editor;
            editor.on('focus', function (e) {
                focusedEditor = e.editor.name;
            });
        });
    </script>
@endsection

@section('content')
    @include('team::admin.breadcrumbs')
    @include('admin.notify')
    <form class="my-form" action="{{ route('admin.team.update', ['id' => $teamMember->id]) }}" method="POST" data-form-type="store" enctype="multipart/form-data" autocomplete="off">
        <span class="hidden curr-editor"></span>
        <div class="col-xs-12 p-0">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="position" value="{{old('position')}}">
            @include('admin.partials.on_edit.form_actions_top')
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
                        @php
                            $teamMemberTranslate = is_null($teamMember->translate($language->code)) ? $teamMember : $teamMember->translate($language->code);
                        @endphp
                        <div id="{{$language->code}}" class="tab-pane fade in @if($language->code === config('default.app.language.code')) active @endif">
                            @include('admin.partials.on_edit.form_fields.input_text', ['fieldName' => 'title_' . $language->code, 'label' => trans('team::admin.team.title'), 'required' => true, 'model' => $teamMemberTranslate])
                            @include('admin.partials.on_edit.form_fields.textarea', ['fieldName' => 'announce_' . $language->code, 'rows' => 9, 'label' => trans('admin.announce'), 'required' => false, 'model' => $teamMemberTranslate])
                            @include('admin.partials.on_edit.form_fields.textarea', ['fieldName' => 'description_' . $language->code, 'rows' => 9, 'label' => trans('admin.description'), 'required' => false, 'model' => $teamMemberTranslate])
                            @include('admin.partials.on_edit.show_in_language_visibility_checkbox', ['fieldName' => 'visible_' . $language->code, 'model' => $teamMember])
                        </div>
                    @endforeach
                </div>
                <ul class="nav nav-tabs-second">
                    @foreach($languages as $language)
                        <li @if($language->code === config('default.app.language.code')) class="active" @endif><a langcode="{{$language->code}}">{{$language->code}}</a></li>
                    @endforeach
                </ul>
                <hr>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        @include('admin.partials.on_edit.form_fields.input_text', ['fieldName' => 'phone', 'label' => trans('team::admin.team.phone'), 'required' => false, 'model' => $teamMember])
                    </div>
                    <div class="col-md-6 col-xs-12">
                        @include('admin.partials.on_edit.form_fields.input_text', ['fieldName' => 'email', 'label' => trans('team::admin.team.email'), 'required' => false, 'model' => $teamMember])
                    </div>
                </div>
                @include('admin.partials.on_edit.seo', ['model' => $teamMember->seoFields])
                <div class="form form-horizontal">
                    <div class="form-body">
                        <hr>
                        @include('admin.partials.on_edit.form_fields.upload_file', ['model' => $teamMember, 'deleteRoute' => route('admin.team.delete-image', ['id'=>$teamMember->id])])
                        @include('admin.partials.on_edit.active_checkbox', ['model' => $teamMember])
                    </div>
                    @include('admin.partials.on_edit.form_actions_bottom')
                </div>
            </div>
        </div>
    </form>
@endsection
