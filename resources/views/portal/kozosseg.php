@extends('portal')

<div class="container inner kozi-adatlap">
    <div class="row">
        <div class="col-md-4">
            <img class="img-big" src="{{ $images[0] }}" style="border-bottom: 3px solid #222;">
            @if(count($images) > 1)
                <div class="kozi-kiskepek row m-0">
                    @foreach($images as $i => $image)
                        <div class="col-lg-3 col-md-6 col-6 p-0"><img @if($i == 0) class="active" @endif src="{{ $image }}"/></div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="col-md-8 pt-4 pt-md-0">
            <div class="title">
                @if($backUrl)
                    <div class="float-right">
                        <a href="{{ $backUrl }}"><i class="fa fa-angle-double-left"></i> vissza</a>
                    </div>
                @endif
                <h2 class="primary-title">{{ $group->name }}</h2>
                <h5 class="subtitle">{{  $group->city . ($group->district ? ', ' . $group->district : '')  }}</h5>
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
                    <a href="@route('portal.groups', ['tags' => $tag['tag']])" class="badge badge-primary">{{ $tag['tag_name'] }}</a>
                @endforeach
            </p>
            {{ $group->description }}
            <p class="mt-4">
                <a href="#" class="btn btn-outline-primary" data-toggle="modal" data-target="#contact-modal"><i class="fas fa-envelope"></i> Érdekel!</a>
                <a href="#" class="text-danger float-right"><i class="fas fa-exclamation-triangle"></i> Jelentem</a>
            </p>
        </div>
    </div>
    <h5 class="mt-4">Hasonló közösségek</h5>
    <div class="card-deck">
        @foreach($similar_groups as $i => $similar_group)
                <a href="{{ $similar_group->url() }}" class="card text-dark mt-4 mt-lg-0">
                    <img class="card-img-top" src="{{ $similar_group->getThumbnail() }}" />
                    <div class="card-body">
                        <h6>{{ $similar_group->name }}</h6>
                        <div class="description" style="font-size: 14px;">
                            {{ $similar_group->excerpt(15) }}
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="kozi-info text-dark">
                            <div><i class="fas fa-map-marker-alt text-danger"></i>
                                <small>{{ $similar_group->city }}</small>
                            </div>
                            <div><i class="fas fa-user-graduate"></i><small>{{ $similar_group->ageGroup() }}</small></div>
                            <div><i class="fas fa-calendar-alt"></i><small>{{ $similar_group->occasionFrequency() }}</small></div>
                        </div>
                    </div>
                </a>
        @endforeach
    </div>
</div>


<div class="modal fade" id="contact-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Vedd fel a kapcsolatot a közzösségvezetővel!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form>
      <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Neved*</label>
                        <input type="text" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email címed*</label>
                        <input type="email" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <textarea class="form-control" rows=5>Kedves {{ $group->group_leaders }}!

Érdeklődni szeretnék, hogy lehet-e csatlakozni a {{ $group->name }} közösségbe?</textarea>
            </div>
            <div class="text-right">
                <p class="mb-0"><label><input type="checkbox"> Nem vagyok robot</label></p>
                <p><label><input type="checkbox"> Az <a href="">adatvédelmi tájékoztatót</a> elolvastam és elfogadom</label></p>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Bezár</button>
        <button type="submit" class="btn btn-primary">Üzenet küldése</button>
      </div>
  </form>

    </div>
  </div>
</div>
