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
