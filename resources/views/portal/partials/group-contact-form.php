<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Neved*</label>
            <input type="text" name="name" class="form-control" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Email címed*</label>
            <input type="email" name="email" class="form-control" required>
        </div>
    </div>
</div>
<div class="form-group">
    <textarea class="form-control" name="message" rows=5 required>Kedves {{ $group->group_leaders }}!

Érdeklődni szeretnék, hogy lehet-e csatlakozni a {{ $group->name }} közösségbe?</textarea>
</div>
<div class="text-right">
    <p><label><input type="checkbox" required> Az <a href="">adatvédelmi tájékoztatót</a> elolvastam és elfogadom</label></p>
</div>
