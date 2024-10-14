<?= $this->extend('layout'); ?>

<?= $this->section('contenido'); ?>
<h3 class="my-3">Agregar Nuevo Equipo</h3>

<?php if (session()->getFlashdata('error') !== null) { ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php } ?>

<form action="<?= base_url('equipos'); ?>" method="post">
    <div class="form-group mb-3">
        <label for="nombre_equipo">Nombre del Equipo</label>
        <input type="text" class="form-control" id="nombre_equipo" name="nombre_equipo" value="<?= old('nombre_equipo'); ?>" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?= base_url('equipos'); ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection(); ?>
