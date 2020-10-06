
<label for="image" class="file-upload form-control text-center" style="cursor: pointer">
    <input type="file" id="{{ $file_input_name }}" name="{{ $file_input_name }}" style="display: none" onchange="loadFile(event, this);" data-target="{{ $file_input_name }}-preview">
    <img src="{{ $file_input_src }}" class="file-upload-image" id="{{ $file_input_name }}-preview">
    <i class="fa fa-image file-upload-placeholder" style="font-size: 5rem"></i>
</label>
