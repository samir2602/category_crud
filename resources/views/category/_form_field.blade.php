<div class="form-group mb-3">    
    <label for="parent_category">Parent Category</label>
    <select class="select2-multiple form-control" name="parent_category[]" multiple="multiple" id="select2Multiple">
        <option value="">Select</option>
        @foreach ($category_list as $cate)            
            <option value="{{$cate->id}}" @isset($parent_ids){{in_array($cate->id, $parent_ids ?: []) ? "selected": ""}}@endisset>{{$cate->name}}</option>
        @endforeach        
    </select>
</div>
<div class="form-group mb-3">
    <label for="name">Enter Name</label>
    <input type="text" class="form-control" name="name" id="name" aria-describedby="emailHelp" placeholder="Enter Name" value="@isset($category->name){{$category->name}}@else{{old('name')}}@endisset">    
    @error('name')
    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
    @enderror
</div>
<button type="submit" class="btn btn-primary">Submit</button>