<?= $this->extend('layout'); ?>

<?= $this->section('contenido'); ?>
<h3 class="my-3">Agregar Nuevo Usuario</h3>

<?php if (session()->getFlashdata('error') !== null) { ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php } ?>

<form action="<?= base_url('usuarios'); ?>" method="post">
    <div class="form-group mb-3">
        <label for="nombre">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= old('nombre'); ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="apellido">Apellido</label>
        <input type="text" class="form-control" id="apellido" name="apellido" value="<?= old('apellido'); ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="correoElectronico">Correo Electrónico</label>
        <input type="email" class="form-control" id="correoElectronico" name="correoElectronico" value="<?= old('correoElectronico'); ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="telefono">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" value="<?= old('telefono'); ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="contrasena">Contraseña</label>
        <input type="password" class="form-control" id="contrasena" name="contrasena" required>
    </div>

    <button type="submit" class="btn btn-primary">Guardar Usuario</button>
    <a href="<?= base_url('usuarios'); ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection(); ?>
