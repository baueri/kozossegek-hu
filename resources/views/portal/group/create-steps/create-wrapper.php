@section('subtitle', 'Új közösség regisztrálása | ')
@extends('portal')
@featuredTitle('Új közösség regisztrálása')
<div class="container-fluid inner pt-4 pb-4" id="create-group">
    @message()
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
