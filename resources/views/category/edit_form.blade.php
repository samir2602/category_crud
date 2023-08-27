@extends('layouts.app')

@section('content')
<form action="{{route('category.update',$category->id)}}" method="POST">
  @csrf
  @method('PUT')
  @include('category._form_field')
</form>
@endsection()

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Select2 Multiple
        $('.select2-multiple').select2({
            placeholder: "Select",
            allowClear: true
        });

    });
</script>
@endpush