@extends('portal')

<div class="container inner kozi-adatlap">
    <div class="row">
        <div class="col-md-4">
            <img class="img-big" src="https://picsum.photos/650/650?random=0" style="border-bottom: 3px solid #222;">
            <div class="kozi-kiskepek">
                <img class="active" src="https://picsum.photos/650/650?random=0" style="width:25%; float:left;" />
                <img src="https://picsum.photos/650/650?random=1" style="width:25%; float:left;" />
                <img src="https://picsum.photos/650/650?random=2" style="width:25%; float:left;" />
                <img src="https://picsum.photos/650/650?random=3" style="width:25%; float:left;" />
            </div>
        </div>
        <div class="col-md-8">
            <div class="title">
                @if($backUrl)
                    <div class="float-right">
                        <a href="{{ $backUrl }}"><i class="fa fa-angle-double-left"></i> vissza</a>
                    </div>
                @endif
                <h2 class="primary-title">{{ $group->name }}</h2>
                <h5 class="subtitle">{{ $group->city }}</h5>
            </div>
            <p class="kozi-tulajdonsag">
                <label>Helyszín:</label> {{ $institute->city }}, {{ $institute->name }}
            </p>
            <p class="kozi-tulajdonsag">
                <label>Alkalmak gyakorisága:</label> {{ $group->occasionFrequency() }}
            </p>
            <p class="kozi-tulajdonsag">
                <label>Korcsoport:</label> {{ $group->ageGroup() }}
            </p>

            <p class="kozi-tulajdonsag">
                <label>Közösségvezető(k):</label> {{ $group->group_leaders }}
            </p>
            <p class="kozi-tulajdonsag">
                <label>Címkék</label><br>
                @foreach($tag_names as $tag)
                    <a href="@route('portal.groups', ['tag' => $tag['slug']])" class="badge badge-primary">{{ $tag['tag'] }}</a>
                @endforeach
            </p>
            {{ $group->description }}
            <p class="mt-4">
                <a href="#" class="btn btn-outline-primary"><i class="fas fa-envelope"></i> Érdekel!</a>
                <a href="#" class="text-danger float-right"><i class="fas fa-exclamation-triangle"></i> Jelentem</a>
            </p>
        </div>
    </div>
</div>
