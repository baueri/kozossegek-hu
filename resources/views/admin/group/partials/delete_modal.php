<div class="row">
    <div class="col-md-12">
        @alert('info')
        A közösségvezető a sikeres művelet esetén az alábbi email címen értesítve lesz arról, hogy a közössége törlésre került összeférhetetlenség miatt.
        További indoklást javasolt az üzenethez írni.
        @endalert
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Címzett neve</label>
            <input type="text" name="name" class="form-control" value="{{ $group->group_leaders }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Címzett email címe</label>
            <input type="email" name="email" class="form-control" value="{{ $group->group_leader_email }}">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Üzenet tárgya</label>
            <input type="text" name="subject" class="form-control" value="Közösség adatlapja törlésre került">
        </div>
    </div>

    <div class="col-md-12">
        <label>Üzenet </label>
        <textarea name="message" class="form-control" rows="10">{{ $message }}</textarea>
    </div>
</div>
