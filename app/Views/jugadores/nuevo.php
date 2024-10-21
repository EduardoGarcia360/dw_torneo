<?= $this->extend('layout'); ?>

<?= $this->section('contenido'); ?>
<h3 class="my-3">Agregar Nuevo Jugador</h3>

<?php if (session()->getFlashdata('error') !== null) { ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php } ?>

<form action="<?= base_url('jugadores'); ?>" method="post" enctype="multipart/form-data">
    <div class="form-group mb-3">
        <label for="nombres">Nombres</label>
        <input type="text" class="form-control" id="nombres" name="nombres" value="<?= old('nombres'); ?>" required>
    </div>
    <div class="form-group mb-3">
        <label for="apellidos">Apellidos</label>
        <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= old('apellidos'); ?>" required>
    </div>
    <div class="form-group mb-3">
        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= old('fecha_nacimiento'); ?>" required>
    </div>
    <div class="form-group mb-3">
        <label for="equipo_id">Equipo</label>
        <select class="form-control" id="equipo_id" name="equipo_id" required>
            <option value="">Seleccione un equipo</option>
            <?php foreach ($equipos as $equipo) : ?>
                <option value="<?= $equipo['id']; ?>">
                    <?= $equipo['nombre_equipo']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="fotografia">Fotografía</label>
        <input type="file" class="form-control" id="fotografia" name="fotografia">
    </div>
    
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?= base_url('jugadores'); ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection(); ?>
