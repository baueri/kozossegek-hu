@section('header_content')
    @featuredTitle('Közösséget hirdetek')
@endsection
@extends('portal')
<div class="container p-4" id="create-group">
    <div class="row mt-5 mb-5" id="steps">
        @foreach($steps as $step_number => $step_data)
            <div class="col-md-4 @if(count($steps) == 2 && $step_number == 1) offset-2 @endif text-center step {{ $step_data[0] == $step ? 'active' : '' }}">
                <span class="step-number">{{ $step_number }}</span>
                <span class="step-text">{{ $step_data[1] }}</span>
            </div>
        @endforeach
    </div>
    <div>
        @yield('portal.group.create-steps.create-wrapper')
    </div>
    
</div>
<style>
    #steps {
        position: relative;
    }
    #steps:before {
        content: " ";
        position: absolute;
        left: 0;
        width: 100%;
        top: 25px;
        border-bottom: 2px dashed;
        border-color: rgba(0, 0, 0, .3);
    }
    .step-text {
        color: #999;
        font-size: .9rem;
        margin-top: 10px;
        display: block;
    }
    .step-number {
        border-radius: 50%;
        background: #3f91b0;
        color: #fff;
        width: 50px;
        height: 50px;
        display: block;
        margin: auto;
        line-height: 50px;
        font-size: 1.5rem;
    }
    
    .step:not(.active) .step-number {
        filter: grayscale(.6);
    }
    
    .step.active .step-number{
        background: #1c3c4b;
        box-shadow: 0 0 4px #000;
    }
    
    .step.active .step-text{
        color: #000;
    }
    
</style>