@section('header')
    <meta name="keywords" content="{{ $keywords }}" />
    <meta name="description" content="{{ $group->name }}" />
    <meta property="og:url"           content="{{ $group->url() }}" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="kozossegek.hu - {{ $group->name }}" />
    <meta property="og:description"   content="{{ $group->excerpt(20) }}" />
    <meta property="og:image"         content="{{ $group->getThumbnail() }}" />
    <meta property="og:locale"         content="hu_HU" />
    <link rel="canonical" href="{{ $group->url() }}" />
@endsection
@extends('portal')
<?php $nvr = 'a_' . substr(md5(time()), 0, 5); ?>
<script>
    let nvr = "{{ $nvr }}";
</script>
<div class="container inner kozi-adatlap">
    @if($group->status == "inactive")
        @alert('warning')
        Ez a közösséged jelenleg <b>inaktív</b> állapotban van, ezért mások számára nem jelenik meg a találati listában, illetve közvetlenül se tudják megtekinteni az adatlapját. Amennyiben láthatóvá szeretnéd tenni, állítsd át az állapotát <b>aktívra</b> a <a href="{{ $group->getEditUrl() }}" title="szerkesztés">szerkesztési oldalon</a>.
        @endalert
    @endif
    {{ $group->getBreadCrumb() }}
    <div class="row">
        <div class="col-lg-4 d-md-none d-lg-block">
            <div><img class="img-big" src="{{ $group->getThumbnail() }}" alt="{{ $group->name }}"></div>
        </div>
        <div class="col-lg-8 col-md-12 pt-4 pt-md-0">
            <div class="title">
                <img class="img-big img-sm d-none d-md-block d-lg-none" src="{{ $group->getThumbnail() }}" alt="{{ $group->name }}">
                @if($backUrl)
                    <div class="float-right">
                        <a href="{{ $backUrl }}"><i class="fa fa-angle-double-left"></i> vissza</a>
                    </div>
                @endif
                <h1 class="primary-title h2 mb-2">
                    {{ $group->name }}
                    @if($user && $user->id == $group->user_id)
                        <a href="{{ $group->getEditUrl() }}" title="szerkesztés">
                            <i class="fa fa-edit" style="font-size: 18px;"></i>
                        </a>
                    @endif
                </h1>
                <div class="group-tags float-left">
                    @foreach($group->tags as $tag)
                    <a href="@route('portal.groups', ['tags' => $tag['tag']])" class="tag align-bottom">
                        <span class="tag-img tag-{{ $tag['tag'] }}" title="{{ $tag['tag_name'] }}"></span>
                    </a>
                    @endforeach
                </div>
                <div class="float-right mb-2">@facebook_share_button($group->url())</div>
            </div>
            <p class="kozi-tulajdonsag">
                @if($institute)
                    <strong>Helyszín</strong><br/>
                    <a href="{{ $institute->groupsUrl() }}" title="{{ $institute->name }} közösségei">{{ $institute->name }} ({{ $institute->city }})</a><br/>
                    @if($institute->name2)
                        <span style="font-size: 13px">({{ $institute->name2 }})</span>
                    @endif
                @endif
            </p>
            @if($group->spiritual_movement)
                <p class="kozi-tulajdonsag">
                    <strong>Lelkiségi mozgalom</strong><br/> {{ $group->spiritual_movement }}
                </p>
            @endif
            <div class="row" style="margin-bottom: .5em;">
                <div class="col-lg-4 col-md-12 mb-md-2">
                    <strong>Alkalmak gyakorisága</strong><br/>{{ $group->occasionFrequency() }}
                </div>
                <div class="col-lg-3 col-md-12 mb-md-2">
                    <strong>Korcsoport</strong><br/> {{ $group->ageGroup() }}
                </div>
                @if($group->join_mode)
                    <div class="col-lg-5 col-md-12 mb-md-2">
                        <strong>Csatlakozási lehetőség módja</strong><br/> {{ $group->joinMode() }}
                    </div>
                @endif
            </div>
            <p class="kozi-tulajdonsag">
                <strong>Közösségvezető(k)</strong><br/> {{ $group->group_leaders }}
            </p>
            @if($group->description)
                <b>Bemutatkozás</b><br/>
                {{ $group->description }}
            @endif
            <p class="mt-4">
                <span class="btn btn-outline-altblue open-contact-modal"><i class="fas fa-envelope"></i> Érdekel!</span>
            </p>
        </div>
    </div>
    @if($similar_groups)
        <h5 class="mt-4" style="border-bottom: 1px solid;margin-bottom: 1em;padding-bottom: 0.3em;">Hasonló közösségek</h5>
        <div class="row" id="kozossegek-list">
            @foreach($similar_groups as $i => $similarGroup)
            <div class="col-md-3 mb-3">
                <div class="card kozi-box h-100 p-0">
                    <a href="{{ $similarGroup->url() }}" style="background: url({{ $similarGroup->getThumbnail() }}) no-repeat bottom 0 center;background-size: cover; height: 185px" class="card-img">
                        <div>megnézem</div>
                    </a>
                    <div class="card-body">
                        <p class="text-center">
                            @foreach($similarGroup->tags as $tag)
                                <span class="tag-img tag-{{ $tag['tag'] }}" title="{{ $tag['tag_name'] }}"></span>
                            @endforeach
                        </p>
                        <div>{{ $similarGroup->name }}</div>
                        <div class="city">
                            {{ $similarGroup->city . ($similarGroup->district ? ', ' . $similarGroup->district : '')  }}
                        </div>
                        <p class="card-text mb-0">
                            <strong>korosztály:</strong> <span>{{ $similarGroup->ageGroup() }}</span><br>
                            <strong>alkalmak:</strong> <span>{{ $similarGroup->occasionFrequency() }}</span><br>
                        </p>
                        <a href="{{ $similarGroup->url() }}" class="btn btn-outline-success btn-sm kozi-more-info">Megnézem</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>


<div class="modal fade" id="contact-modal" tabindex="-1" role="dialog" aria-labelledby="contact-group" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="contact-group">Vedd fel a kapcsolatot a közzösségvezetővel!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Bezár</button>
        <button type="submit" class="btn btn-primary">Üzenet küldése</button>
      </div>
  </form>

    </div>
  </div>
</div>
<style>
    #{{ $nvr }} {
        width: 0px;
        padding: 0;
        border: 0;
        margin: 0;
    }
</style>
<script>
    $(() => {
        $(".open-contact-modal").click(function(){
            $.post("@route('portal.group-contact-form', ['kozosseg' => $slug])", function(form) {
                $("#contact-modal .modal-body").html(form);
                $("#contact-modal").modal("show");
            });
        });

        $("#contact-modal form").submit(function(e) {
            e.preventDefault();

            var data = {
                name: $("[name=name]").val(),
                email: $("[name=email]").val(),
                message: $("[name=message]").val(),
                website: $("[name=website]").val()
            }
            $.post("@route('portal.contact-group', $group)", data, function(response) {
                if (response.success) {
                    $("#contact-modal .modal-body").html(response.msg);
                    $("#contact-modal [type=submit]").remove();
                } else {
                    dialog.danger({
                        message: 'Nem sikerült elküldeni az üzenetet, kérjük, próbáld meg később!',
                        size: 'md'
                    }, m => m.closeAll());
                }
            }).fail(() => {
                dialog.danger({
                    message: 'Nem sikerült elküldeni az üzenetet, kérjük, próbáld meg később!',
                    size: 'md'
                }, m => m.closeAll());
            });
        });
    })
</script>
