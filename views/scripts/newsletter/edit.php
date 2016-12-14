<div class="container">
    <?php if ($this->user): ?>
        <form class="form" method="post" action="">
            <div class="form-group">
                <label for="gender">Anrede</label>
                <select id="gender" name="gender" class="form-control">
                    <?php if ($this->user->getGender() == 'Herr'): ?>
                        <option value="male" selected>Herr</option>
                        <option value="female">Frau</option>
                    <?php else: ?>
                        <option value="male">Herr</option>
                        <option value="female" selected>Frau</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="firstname">Vorname</label>
                <input type="text" name="firstname" class="form-control" id="firstname" placeholder="Hans" value="<?= $this->user->getFirstname(); ?>">
            </div>
            <div class="form-group">
                <label for="lastname">Nachname</label>
                <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Müller" value="<?= $this->user->getLastname(); ?>">
            </div>
            <div class="form-group">
                <label for="email">E-Mail</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="example@domain.com" value="<?= $this->user->getEmail(); ?>">
            </div>
            <button type="submit" class="btn btn-default">Daten bestätigen</button>
        </form>
    <?php endif; ?>
</div>
