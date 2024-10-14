<?= $this->extend('layout'); ?>

<?= $this->section('contenido'); ?>
<h3 class="my-3">Editar Jornada</h3>

<?php if (session()->getFlashdata('error') !== null) { ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php } ?>

<form action="<?= base_url('jornadas/'.$jornada['id']); ?>" method="post">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="jornada_id" value="<?= $jornada['id']; ?>">
    
    <div class="form-group mb-3">
        <label for="numero_jornada">Número de Jornada</label>
        <input type="number" class="form-control" id="numero_jornada" name="numero_jornada" value="<?= old('numero_jornada', $jornada['numero_jornada']); ?>" required>
    </div>
    <div class="form-group mb-3">
        <label for="fecha_juego">Fecha de Juego</label>
        <input type="date" class="form-control" id="fecha_juego" name="fecha_juego" value="<?= old('fecha_juego', $jornada['fecha_juego']); ?>" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <a href="<?= base_url('jornadas'); ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection(); ?>
