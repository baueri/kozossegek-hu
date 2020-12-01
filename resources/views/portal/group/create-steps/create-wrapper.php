@section('header_content')
    @featuredTitle('Közösséget hirdetek')
@endsection
@extends('portal')
<div class="container p-4">
    <div class="row mt-5 mb-5" id="steps">
        <div class="col-md-4 text-center step {{ $step == 1 ? 'active' : '' }}">
            <span class="step-number">1</span>
            <span class="step-text">Felhasználói adatok</span>
        </div>
        <div class="col-md-4 text-center step {{ $step == 2 ? 'active' : '' }}">
            <span class="step-number">2</span>
            <span class="step-text">Közösség adatainak megadása</span>
        </div>
        <div class="col-md-4 text-center step {{ $step == 3 ? 'active' : '' }}">
            <span class="step-number">3</span>
            <span class="step-text">Regisztráció befejezése</span>
        </div>
    </div>
    @yield('portal.group.create-steps.create-wrapper')
    <div class="mt-4">
        <a href="" class="btn btn-default">vissza</a>
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