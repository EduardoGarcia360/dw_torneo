<?= $this->extend('layout'); ?>

<?= $this->section('contenido'); ?>
<h3 class="my-3">Editar Equipo</h3>

<?php if (session()->getFlashdata('error') !== null) { ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php } ?>

<form action="<?= base_url('equipos/'.$equipo['id']); ?>" method="post">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="equipo_id" value="<?= $equipo['id']; ?>">
    
    <div class="form-group mb-3">
        <label for="nombre_equipo">Nombre del Equipo</label>
        <input type="text" class="form-control" id="nombre_equipo" name="nombre_equipo" value="<?= old('nombre_equipo', $equipo['nombre_equipo']); ?>" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <a href="<?= base_url('equipos'); ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection(); ?>
