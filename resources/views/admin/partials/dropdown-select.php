<div class="dropdown">
    <button type="button" class="dropdown-toggle form-control ml0 mt0" data-toggle="dropdown" aria-expanded="false" id="dropdown-{{ $name }}">
        <span>
            @if(isset($values[$selected_value]))
                {{ $values[$selected_value] }}
            @else
                {{ $values[key($values)] }}
            @endif
        </span>
        <i class="fa fa-angle-down float-right"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdown-{{ $name }}" @if($height) style="overflow-y: auto; height: {{ $height }}px;" @endif>
        <?php foreach($values as $value => $valueText): ?>
            @if($valueText == 'divider')
                <li>
                    <div class="divider"></div>
                </li>
            @else
                <li @class="dropdown-item {{ $selected_value == $value ? 'active' : '' }}">
                    <label for="dropdown-{{ $name . '-' . $value }}">
                        <span>{{ $valuesText }}</span>
                        <input type="radio"
                               class="hidden"
                               name="{{ $name }}"
                               id="dropdown-{{ $name . '-' . $value }}"
                               value="{{ $value }}" @if($selected_value == $value) checked @endif>
                    </label>
                </li>
            @endif
        <?php endforeach; ?>
    </ul>
</div>

