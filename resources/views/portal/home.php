@extends('portal')
<div id="main-finder" class="">
    <div class="container">
        <div class="text-white text-center" style="margin-bottom: 2em;">
            <h1>Találd meg a neked való közösséget!</h1>
            <p>Erre a helyre írjunk valami nagyon motiválót, hogy a látogató érezze, hogy gondolunk rá :-) Lehet jó hosszú, pl, hogy miért fontos közösséghez tartozni, vagy, hogy mi mindent megteszünk annak érdekében, hogy a hozzá legjobban illő közösséget
                találjuk meg.</p>
        </div>
        <form class="row" method="get" action="kozossegek">
            <div class="col-sm-12 col-md-4 ">
                <select class="form-control" name="varos">
                    <option value="">-- város --</option>
                    <option>Budapest</option>
                    <option>Szeged</option>
                    <option>Kecskemét</option>
                    <option>Pécs</option>
                    <option>Sopron</option>
                </select>
            </div>
            <div class="col-sm-12 col-md-4 ">
                <div class="select">
                    <select class="form-control" name="korosztaly">
                        <option value="">-- korosztály --</option>
                        @foreach($age_groups as $age_group)
                            <option value="{{ $age_group->name }}">
                                {{ ucfirst($age_group->translate()) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 ">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search "></i> Keresés</button>
            </div>
        </form>
    </div>
</div>