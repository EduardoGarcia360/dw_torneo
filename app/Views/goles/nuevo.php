<?= $this->extend('layout'); ?>

<?= $this->section('contenido'); ?>
<h3 class="my-3">Agregar Gol</h3>

<?php if (session()->getFlashdata('error') !== null) { ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php } ?>

<form action="<?= base_url('goles'); ?>" method="post">
    <div class="form-group mb-3">
        <label for="jugador_id">Jugador</label>
        <select class="form-control" id="jugador_id" name="jugador_id" required>
            <option value="">Seleccione un jugador</option>
            <?php foreach ($jugadores as $jugador) : ?>
                <option value="<?= $jugador['id']; ?>">
                    <?= $jugador['nombres'] . ' ' . $jugador['apellidos']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="jornada_id">Jornada</label>
        <select class="form-control" id="jornada_id" name="jornada_id" required>
            <option value="">Seleccione una jornada</option>
            <?php foreach ($jornadas as $jornada) : ?>
                <option value="<?= $jornada['id']; ?>">
                    Jornada <?= $jornada['numero_jornada']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="cantidad_goles">Cantidad de Goles</label>
        <input type="number" class="form-control" id="cantidad_goles" name="cantidad_goles" value="<?= old('cantidad_goles'); ?>" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?= base_url('goles'); ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection(); ?>
