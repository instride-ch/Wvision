<div class="container">
    <form class="form" method="post" action="">
        <div class="form-group">
            <label for="gender">Anrede</label>
            <select id="gender" name="gender" class="form-control">
                <option value="male" selected>Herr</option>
                <option value="female">Frau</option>
            </select>
        </div>
        <div class="form-group">
            <label for="firstname">Vorname</label>
            <input type="text" name="firstname" class="form-control" id="firstname" placeholder="Hans">
        </div>
        <div class="form-group">
            <label for="lastname">Nachname</label>
            <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Müller">
        </div>
        <div class="form-group">
            <label for="email">E-Mail</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="example@domain.com">
        </div>
        <button type="submit" class="btn btn-default">Registrieren</button>
    </form>
</div>
