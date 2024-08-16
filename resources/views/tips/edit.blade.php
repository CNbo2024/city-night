@extends('admin.template')

@section('main')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Edit Tip</h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <form class="svform" action="{{url('admin/tips' . $tip->id . '/update')}}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="box-body">
                            <div class="form-group col-md-12">
                                <label for="title" class="control-label col-sm-3">
                                    Título <span class="text-danger">*</span>
                                </label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="title" id="title" value="{{ $tip->title }}">
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="title" class="control-label col-sm-3">
                                    Cotenido <span class="text-danger">*</span>
                                </label>

                                <div class="col-sm-8">
                                    <textarea name="content" class="form-control">{{ $tip->content }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-default">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.tiny.cloud/1/w7sjzxkyuri4v6wqdo34ispetrbdnpxco4dn1swmrmweoarf/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
      tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
          { value: 'First.Name', title: 'First Name' },
          { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
      });
    </script>
@endpush
