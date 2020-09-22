@extends('portal')

<div class="container inner kozi-adatlap">
    <div class="row">
        <div class="col-md-4">
            <!--<img src="/images/kozossegek/puszta.png"/>-->
            <img src="https://picsum.photos/650/650?random=0" style="border-bottom: 3px solid #222;">
            <div class="kozi-kiskepek">
                <img class="active" src="https://picsum.photos/650/650?random=0" style="width:25%; float:left;" />
                <img src="https://picsum.photos/650/650?random=1" style="width:25%; float:left;" />
                <img src="https://picsum.photos/650/650?random=2" style="width:25%; float:left;" />
                <img src="https://picsum.photos/650/650?random=3" style="width:25%; float:left;" />
            </div>

        </div>
        <div class="col-md-8">
            <div class="title">
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
                <a href="#" class="badge badge-secondary">Fiatal felnőtt</a>
                <a href="#" class="badge badge-secondary">Jezsuita lelkiség</a>
                <a href="#" class="badge badge-secondary">Katolikus</a>
                <a href="#" class="badge badge-secondary">Közvetlen</a>
                <a href="#" class="badge badge-secondary">Nyitott</a>
            </p>
            {{ $group->description }}
            <p>
                <a href="#" class="btn btn-outline-primary"><i class="fas fa-comment-dots"></i> Felveszem a kapcsolatot</a>
                <a href="#" class="text-danger float-right"><i class="fas fa-exclamation-triangle"></i> Jelentem</a>
            </p>
        
        </div>
    </div>
</div>